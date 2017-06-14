<?php
/**
 * Plugin Name: Global Translator
* Plugin URI: http://www.globaltranslatorplugin.com
* Description: Automatically translates a blog in 63 different languages by wrapping the powerful "Yandex Translate" translation engine. After uploading this plugin click 'Activate' and then afterwards you must <a href="admin.php?page=gltr-plugin-configuration">visit the configuration page</a> and enter your preferences to enable the translator.
* Version: 2.0.2
* Author: Davide Pozza
* Disclaimer: Use at your own risk. No warranty expressed or implied is provided. The author will never be liable for any loss of profit, physical or psychical damage, legal problems. The author disclaims any responsibility for any action of final users. It is the final user's responsibility to obey all applicable local, state, and federal laws.
*/

define('GLTR_DEBUG', false);
define('GLTR_TEXT_SEP', '^|^');
/////////////////////////////////////////////////////////////////////////

$gltr_default_options = array(
"api-keys" => "",
"base_lang" => 'en',
"engine_enabled" => false,
"preferred_languages"=>array(),
"show_credits" => false,
"recent_translations"=>array()
);
add_option('gltr-options',$gltr_default_options);

/////////////////////////////////////////////////////////////////////////
require_once(dirname(__FILE__).'/common.php');
require_once(dirname(__FILE__).'/SmartDOMDocument.php');
/////////////////////////////////////////////////////////////////////////

require_once(dirname(__FILE__).'/widget.php');
add_action( 'widgets_init', 'gltr_register_widget' );
function gltr_register_widget() {
	register_widget( 'Gltr_Widget' );
}

/////////////////////////////////////////////////////////////////////////

register_activation_hook( __FILE__, 'gltr_install' );
function gltr_install(){
	_log('gltr_install::begin');
	global $wpdb, $gltr_supported_langs;
	
	$charset_collate = $wpdb->get_charset_collate();
	
	$table_name = $wpdb->prefix . 'gltr_keys';
	$sql_keys = "CREATE TABLE $table_name (
	id mediumint(9) NOT NULL AUTO_INCREMENT,
	creation_time datetime,
	last_succ_response_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	last_response_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	last_response_code varchar(4) DEFAULT '200' NOT NULL,
	apikey varchar(128) NOT NULL,
	total_succ_api_calls bigint DEFAULT 0 NOT NULL,
	total_succ_translated_chars bigint DEFAULT 0 NOT NULL, 
	PRIMARY KEY (id)
	) $charset_collate;";
	
	$table_name = $wpdb->prefix . 'gltr_queue';
	$sql_queue = "CREATE TABLE $table_name (
	id mediumint(9) NOT NULL AUTO_INCREMENT,
	creation_time datetime,
	slug varchar(512) NOT NULL,
	content longtext,
	lang varchar(4) NOT NULL,
	priority mediumint(9) NOT NULL,
	PRIMARY KEY (id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	dbDelta( $sql_keys );	
	dbDelta( $sql_queue );	
	
	$blog_langs = explode('-',get_bloginfo( 'language' ));
	if (count($blog_langs) == 2){
		$blog_lang = strtolower($blog_langs[0]);
		if (array_key_exists($blog_lang, $gltr_supported_langs)){
			$options = get_option('gltr-options');
			$options['base_lang'] = $blog_lang;
			update_option("gltr-options",$options);
		}
	}

	if (!wp_next_scheduled('gltr_translate')) {
		gltr_debug("Scheduling gltr_translate");
		wp_schedule_event(time(), 'fourminutes', 'gltr_translate');
	}else{
		gltr_debug("NOT Scheduling gltr_translate");
	}
	
	
	_log('gltr_install::end');
}

/////////////////////////////////////////////////////////////////////////

register_deactivation_hook(__FILE__, 'gltr_uninstall');
function gltr_uninstall(){
	//delete_option("gltr-options");

	wp_clear_scheduled_hook('gltr_translate');
}

/////////////////////////////////////////////////////////////////////////

add_action('template_redirect', 'gltr_template_redirect', 0);
function gltr_template_redirect(){
	if (!wp_next_scheduled('gltr_translate')) {
		gltr_debug("Scheduling AGAIN gltr_translate");
		wp_schedule_event(time(), 'fourminutes', 'gltr_translate');
	}
	
	global $gltr_ob_started;
	//_log('template_redirect for :: '.gltr_get_slug());
	$home_root = parse_url(get_option('home'), PHP_URL_PATH);
	if ($_SERVER['REQUEST_METHOD'] == 'POST' ||
		!empty($_SERVER['QUERY_STRING']) ||
		is_user_logged_in() ||
		is_trackback() ||
		is_feed()	||
		is_comment_feed() ||
		is_robots() ||
		is_404() ||
		'' == get_option( 'permalink_structure' ) ||
		substr($_SERVER['REQUEST_URI'], 0, strlen($home_root) + 8) == ($home_root . '/sitemap')){
		//no translations here!
		//_log('no translations here!:'.gltr_get_slug());
	}else{
		$gltr_ob_started = true;
		ob_start('gltr_enqueue_translation');
	}
}

add_action('shutdown', 'gltr_buffer_stop', 1000);
function gltr_buffer_stop(){
	ob_end_flush();
}

add_filter( 'the_content', 'gltr_the_content_filter', 1);
function gltr_the_content_filter($content){
	if (is_single()||is_page())
		return $content."<!--GLTR_END_CONTENT-->";
	else 
		return $content;
}


function gltr_sanitize_html($html){
	$buffer = Gltr_Minify_HTML::minify($html);
	return $buffer;
}

function gltr_html_to_text($html){
	
	$dom = new SmartDOMDocument();
	$dom->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8"));
	$xpath = new DOMXPath($dom);
	
	$textnodes = array();
	foreach ($xpath->query("//meta[@name='description']") as $meta) {
		$desc = $meta->getAttribute('content');
		$textnodes[] = $desc;
	}
	foreach ($xpath->query("//text()[not(ancestor::script) and not (ancestor::style) and not (ancestor::code) and not (ancestor::pre)]") as $textnode) {
		$textnodes[] = $textnode->nodeValue;
		//_log(">>".$textnode->nodeValue."\n\n");
	}
	/*
	$fulltext = '';
	foreach($textnodes as $textnode){
		$fulltext .= '§'.$textnode;
	}*/
	$fulltext = implode(GLTR_TEXT_SEP, $textnodes);
	_log("FULLTEXT>>".$fulltext."\n\n");
	$total_len = strlen($fulltext);
	$chunks = array();
	if ($total_len > 10000){
		end($textnodes);
		$last = key($textnodes);
		$chunk = "";
		foreach ($textnodes as $i => $textnode){
			$chunk .= $textnode;
			if ($i>0 && strlen($chunk)>0)
				$chunk .= GLTR_TEXT_SEP;
			if ($i != $last) {
				//not last element
				if ( strlen($chunk) <= 10000 && strlen($chunk.GLTR_TEXT_SEP.$textnodes[$i+1]) > 10000){
					$chunks[] = $chunk;
					$chunk = "";
				}			
			}else{				
				$chunks[] = $chunk;//TODO if the last chunk > 10000...we have a problem...
			}
		}
	}else{
		$chunks[] = $fulltext;
	}
	
	return $chunks;
}

function gltr_html_to_text_array($html){

	$dom = new SmartDOMDocument();
	$dom->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8"));
	$xpath = new DOMXPath($dom);

	$textnodes = array();
	foreach ($xpath->query("//meta[@name='description']") as $meta) {
		$desc = $meta->getAttribute('content');
		$textnodes[] = $desc;
	}
	foreach ($xpath->query("//text()[not(ancestor::script) and not (ancestor::style) and not (ancestor::code) and not (ancestor::pre)]") as $textnode) {
		$textnodes[] = $textnode->nodeValue;
		//_log(">>".$textnode->nodeValue."\n\n");
	}
	$fulltext = implode('', $textnodes);
	$total_len = strlen($fulltext);
	$chunks = array();
	if ($total_len > 10000){
		end($textnodes);
		$last = key($textnodes);
		$chunk = array();
		$size = 0;
		foreach ($textnodes as $i => $textnode){
			$chunk[] = $textnode;
			$size += strlen($textnode);
			if ($i != $last) {
				//not last element
				if ( $size <= 10000 && $size+strlen($textnodes[$i+1]) > 10000){
					$chunks[] = $chunk;
					$size = 0;
					$chunk = array();
				}
			}else{
				$chunks[] = $chunk;//TODO if the last chunk > 10000...we have a problem...
			}
		}
	}else{
		$chunks[] = $textnodes;
	}
	
	return $chunks;
}

function gltr_text_to_html($translated_texts, $html,$page){
	$lang = $page->lang;
	$slug = $page->slug;
	$texts = array();
	foreach($translated_texts as $translated_text)
		$texts = array_merge($texts, $translated_text);
	$options = get_option('gltr-options');
	$dom = new SmartDOMDocument();
	$dom->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8"));
	$xpath = new DOMXPath($dom);
	_log("TRANSLATION RESULT:: \n".implode('',$texts));
	
	$counter = 0;
	foreach ($xpath->query("//meta[@name='description']") as $node) {
		$node->setAttribute('content',$texts[$counter++]);
		_log("setting meta >> ".$node->getAttribute('content'));
	}
	foreach($xpath->query("//text()[not(ancestor::script) and not (ancestor::style) and not (ancestor::code) and not (ancestor::pre)]") as $node){
		$newNode  = $dom->createDocumentFragment();
		$newNode->appendXML($texts[$counter++]);
		$node->parentNode->replaceChild($newNode, $node);
	}
	foreach ($dom->getElementsByTagName('a') as $item) {
		$link = $item->getAttribute('href');
		//_log("found link $link");
		$home_url = home_url( '/' );
		if (substr($link, 0, strlen($home_url)) === $home_url){
			if (preg_match('/[^\?\.]/', $link) && strpos($link,'/wp-content/')===false) {
				$new_link = str_replace( home_url( '/' ), home_url( '/' ).$lang.'/', $link );
				$item->setAttribute('href', $new_link);
				//_log("Replacing link $link => $new_link");
			}
		}
	}
	foreach ($dom->getElementsByTagName('html') as $item) {
		$orig_lang = $item->getAttribute('lang');
		$item->setAttribute('lang', $lang);
	}
	$result = $dom->saveHTML();
	if ($options['show_credits'])
		$result = str_replace("<!--GLTR_END_CONTENT-->", "<p>Translated by <a href='http://translate.yandex.com/' target='_blank' rel='nofollow'>Yandex.Translate</a> and <a target='_blank' href='http://www.globaltranslatorplugin.com'>Global Translator</a></p>", $result);
	if (preg_match("/(<!--GLTR_BEGIN_WIDGET-->)(.*)(<!--GLTR_END_WIDGET-->)/", $result)){
		$search = "/(<!--GLTR_BEGIN_WIDGET-->)(.*)(<!--GLTR_END_WIDGET-->)/";
		$replace = gltr_get_flags_bar($page->slug);
		$result = preg_replace($search, $replace, $result);
	}
	return $result;
}

function gltr_enqueue_translation($page_content){
	global $paged;
	if (gltr_get_key() === false){
		_log('gltr_enqueue_translation::skipping because not available keys');
	}else if (strlen(trim($page_content))==0){
		_log('gltr_enqueue_translation::empty buffer...skipping');
	}else{
		$options = get_option('gltr-options');
		$slug = gltr_get_slug();
		foreach($options['preferred_languages'] as $lang){
			$file = gltr_get_cache_file($lang,$slug);
			if (file_exists($file)){
				_log("gltr_enqueue_translation::NOT Enqueuing translation req $slug,$lang");
				gltr_confirm_page_translation($slug,$lang);
			}else{				
				if (gltr_enqueue_page($slug,$page_content,$lang,gltr_get_priority()) === false){
					_log("gltr_enqueue_translation::Not enqueued translation req $slug,$lang");
				}else{
					_log("gltr_enqueue_translation::Enqueued '$lang' translation of ".strlen($page_content)." for slug: $slug");
				}
			}
		}
	}
	return $page_content;
}

function  gltr_get_priority(){
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$priority = 10;
	if (is_single())
		$priority = 1;
	else if (is_home())
		$priority = 0;
	else if ($paged == 1 && (is_category()||is_tag()||is_archive()))
		$priority = 2;
	return $priority;
}

add_action( 'gltr_translate', 'gltr_translate' );
function gltr_translate(){
	$options = get_option('gltr-options');
	if ($options['engine_enabled'] == 0){
		_log('gltr_translate::skipping because engine is deactivated');
		return;
	}
	if (gltr_get_key() === false){
		_log('gltr_translate::skipping because not available keys');
		return;
	}
	$page = gltr_get_next_page();
	if ($page === false){
		_log('gltr_translate::no enqueued translations');
		return;
	}
	
	$page_content = $page->content;
	$lang = $page->lang;
	$slug = $page->slug;
	
	
	if (strlen(trim($page_content))==0){
		_log("gltr_translate::skipping because we have an empty content for $lang translation for ".$slug);
		gltr_confirm_page_translation($slug,$lang);
		return;
	}
	/*
	$diff = time() - $options['latest_succ_trans_time'];
	//_log('gltr_translate:: diff::'.$diff.' text size::'.strlen($page_content));
	if ($diff < 60*1){
		_log('gltr_translate::skipping due to a low delay:'.$diff."secs");
		return;
	}*/
	
	$base_lang=$options['base_lang'];
	
	$file = gltr_get_cache_file($lang,$slug,true);
	if (file_exists($file)){
		_log("gltr_translate::skipping because we already have a valid $lang translation for ".$slug);
		gltr_confirm_page_translation($slug,$lang);
		return;
	}
	//$page_content = gltr_sanitize_html($page_content);
	//$page_content = str_replace('&','^amp^',$page_content);
	$chunks = gltr_html_to_text_array($page_content);
	
	$responses = array();
	
	
	foreach($chunks as $tobetranslated){
		//$tobetranslated = str_replace('^amp^','&',$tobetranslated);
		//_log("Translating:::".$tobetranslated);
		//if (strlen($tobetranslated)==0)continue;//WHY?????
		if (count($tobetranslated)==0 || strlen($tobetranslated[0])==0)continue;//WHY?????
		_log("gltr_translate::sending chunk having size ".count($tobetranslated));
		$url = "https://translate.yandex.net/api/v1.5/tr.json/translate";
		$key = gltr_get_key();
		_log("sending text of ".gltr_array_total_chars($tobetranslated)." chars for '$lang' language and slug:'$slug' using key: ".$key);
		
		$query = http_build_query (array( 
				'key' => $key, 
				'lang' => $base_lang.'-'.$lang,
				'format' => 'html' ));
		
		foreach($tobetranslated as $text){
			$text = http_build_query(array('text' => $text));
			$query .= '&'.$text;
			
		}
		//_log("Query::".$query);
		
		$args = array(
			'method' => 'POST',
			'timeout' => 25,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking' => true,
			'headers' => array('User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:12.0) Gecko/20100101 Firefox/21.0'),
				/*
			'body' => array( 
				'key' => $key, 
				'lang' => $base_lang.'-'.$lang,
				'text' => $tobetranslated,
				'format' => 'html' ),
				*/
			'body' => $query,
			'cookies' => array()
		);
		//$response = gltr_post($url,$query);
		$response = wp_remote_post($url,$args);
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			_log( "Something went wrong: $error_message");
			$responses = array();
			break;//undo
		} else if (isset($response['response'])){
			$response_status = $response['response']['code'];
			_log("gltr_translate::response_status=".$response_status);
			if ($response_status == 200){
				gltr_update_key($key,$response_status,gltr_array_total_chars($tobetranslated));				
				$json = json_decode($response['body']);
				$text = array();
				if (is_array($json->text)){
					_log("gltr_translate::received json array having size:".count($json->text));
					$text = $json->text;
				}
				//$text = str_replace('&lt;&gt;','<>',$text);
				//$text = str_replace('&lt;|&gt;','<|>',$text);
				
				$responses[] = $text;
				//_log('==>'.$text);
			}else{
				gltr_update_key($key,$response_status);
			}
		}
	}
	if (count($responses) > 0){
		@file_put_contents($file, gltr_text_to_html($responses,  $page_content, $page));
		_log("gltr_translate:: cached $slug, $lang on ".$file);
		gltr_confirm_page_translation($slug, $lang, true);
	}
	//$options['latest_succ_trans_time'] = time();
	//update_option("gltr-options",$options);

}
function gltr_post($url,$query){
	// Create Http context details
	$contextData = array (
			'timeout' => 25,
			'method' => 'POST',
			'header' => "Connection: close\r\n".
			"Content-Length: ".strlen($query)."\r\n",
			'content'=> $query );
	
	// Create context resource for our request
	$context = stream_context_create (array ( 'http' => $contextData ));
	
	// Read page rendered as result of your POST request
	$result =  file_get_contents (
			$url,  
			false,
			$context);

	_log("RAW RESPONSE::\n$result");
	return array('response'=>$result);
}

function gltr_array_total_chars($array){
	if (is_array($array))
		return (strlen(implode('',$array)));
	else 	
		return strlen($array);
}

add_action('wp_head', 'gltr_head',0);
function gltr_head(){
	$options = get_option('gltr-options');
	$slug = gltr_get_slug();
	foreach($options['preferred_languages'] as $lang) {		
		if (gltr_exists_translation($slug, $lang))
			echo('<link rel="alternate" hreflang="'.$lang.'" href="'.home_url( '/' ).$lang.'/'.$slug.'" />'."\n");
	}
	echo('<link rel="alternate" hreflang="'.$options['base_lang'].'" href="'.home_url( '/' ).$slug.'" />'."\n");
}

function gltr_exists_translation($slug,$lang){
	$options = get_option('gltr-options');
	$filename = gltr_get_cache_file($lang, $slug);
	$filename_stale = gltr_get_stale_file($lang, $slug);
	return (file_exists($filename) || file_exists($filename_stale));
}

function gltr_get_cache_flags_bar_path($slug,$create_dir=false){
	$aval_transl = gltr_get_avail_transl($slug);
	$slug = rtrim($slug,'/').'/flag_'.implode($aval_transl).'.png';
	$dir = gltr_get_root_cache_dir().'/flag_bars/'.dirname($slug);
	if (!is_dir($dir) && $create_dir) {
		wp_mkdir_p($dir);
	}
	$slug = ltrim($slug,'/');
	$file = gltr_get_root_cache_dir().'/flag_bars/'.$slug;
	//_log("gltr_get_cache_file :: $file");
	return strtolower($file);
}

function gltr_get_cache_flags_bar_url($slug){
	$aval_transl = gltr_get_avail_transl($slug);
	$slug = rtrim($slug,'/').'/flag_'.implode($aval_transl).'.png';
	$url = gltr_get_root_cache_url().'/flag_bars/'.$slug;
	return strtolower($url);
}

function gltr_get_cache_file($lang,$slug,$create_dir=false){
	$slug = rtrim($slug,'/').'/index.html';
	$dir = gltr_get_root_cache_dir().'/main/'.$lang.'/'.dirname($slug);
	if (!is_dir($dir) && $create_dir) {
		wp_mkdir_p($dir);
	}
	$slug = ltrim($slug,'/');
	$file = gltr_get_root_cache_dir().'/main/'.$lang.'/'.$slug;
	//_log("gltr_get_cache_file :: $file");
	return strtolower($file);
}

function gltr_get_stale_file($lang,$slug,$create_dir=false){
	$slug = rtrim($slug,'/').'/index.html';
	$dir = gltr_get_root_cache_dir().'/stale/'.$lang.'/'.dirname($slug);
	if (!is_dir($dir) && $create_dir) {
		wp_mkdir_p($dir);
	}
	$slug = ltrim($slug,'/');
	$file = gltr_get_root_cache_dir().'/stale/'.$lang.'/'.$slug;
	//_log("gltr_get_stale_file :: $file");
	return strtolower($file);
}


function gltr_get_slug() {
	$self_url = rtrim(gltr_get_self_url(),'/');
	$home_url = home_url( '/' );
	return substr($self_url, strlen($home_url));
}

function gltr_get_self_url() { 
	$full_url = 'http';
	$script_name = '';
	if (isset($_SERVER['REQUEST_URI'])){
		$script_name = $_SERVER['REQUEST_URI'];
	} else {
		$script_name = $_SERVER['PHP_SELF'];
		if ($_SERVER['QUERY_STRING'] > ' ') {
			$script_name .= '?' . $_SERVER['QUERY_STRING'];
		}
	}
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
		$full_url .= 's';
	}
	$full_url .= '://';
	if ($_SERVER['SERVER_PORT'] != '80') {
		$full_url .= $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT'] . $script_name;
	} else {
		$full_url .= $_SERVER['HTTP_HOST'] . $script_name;
	}
	
	return strtok($full_url,'?');//not interested in having the query string
	//return $full_url;
}

add_filter('generate_rewrite_rules', 'gltr_translations_rewrite');
function gltr_translations_rewrite($wp_rewrite) {
	$translations_rules = array(
			'^(' . gltr_langs_pattern() . ')$' => 'index.php?gltr_lang=$matches[1]', 
			'^(' . gltr_langs_pattern() . ')/(.+?)$' => 'index.php?gltr_lang=$matches[1]&gltr_slug=$matches[2]'); 
	$wp_rewrite->rules = $translations_rules + $wp_rewrite->rules;
}
				
function gltr_langs_pattern(){
	global $gltr_supported_langs;
	//_log("patterns::".implode('|',array_keys($gltr_supported_langs)));
	return implode('|',array_keys($gltr_supported_langs));
}

add_filter('query_vars', 'gltr_insert_my_rewrite_query_vars');
function gltr_insert_my_rewrite_query_vars($vars) {
	array_push($vars, 'gltr_lang', 'gltr_slug');
	return $vars;
}

add_action('parse_query', 'gltr_insert_my_rewrite_parse_query',-1);//this action should have the maximum priority!
function gltr_insert_my_rewrite_parse_query($query) {
	//_log('gltr_insert_my_rewrite_parse_query::'.$query->query_vars['gltr_lang']);
	if (isset($query->query_vars['gltr_lang'])) {
		$lang = $query->query_vars['gltr_lang'];
		$slug = $query->query_vars['gltr_slug'];
		$filename = gltr_get_cache_file($lang, $slug);
		$stale_filename = gltr_get_stale_file($lang, $slug);
		//if (is_dir($filename))$filename .= "/index";
		if (file_exists($filename)){
			_log('returning translation cached on :: '.$filename);
			die(file_get_contents($filename));
		}else if(file_exists($stale_filename)){
			_log('returning translation staled on :: '.$stale_filename);
			die(file_get_contents($stale_filename));
		}else{
			header("Location: /$slug", TRUE, 301);
			die();
		}
	}
}		 

add_filter('cron_schedules', 'gltr_new_interval');
function gltr_new_interval($interval) {
	$interval['fourminutes'] = array('interval' => 4*60, 'display' => 'Once every four minutes');
	return $interval;
}

add_action('edit_post', 'gltr_refresh');
add_action('save_post', 'gltr_refresh');
add_action('wp_update_comment_count', 'gltr_refresh');
function gltr_refresh($post_ID){
	_log("gltr_refresh:: post id: $post_ID");	
	//TODO
	//dequeue pages tags home categories contents
	gltr_move_to_stale($post_ID);
}

add_action('comment_post', 'gltr_comment_post');
function gltr_comment_post($comment_id, $status=1) {
	if ($status === 1) {
		$comment = get_comment($comment_id);
		gltr_refresh($comment->comment_post_ID);
	}
}

function gltr_get_root_cache_dir(){
	return WP_CONTENT_DIR . '/gltr_cache/'.strtolower($_SERVER['HTTP_HOST']);
}
function gltr_get_root_cache_url(){
	return content_url() . '/gltr_cache/'.strtolower($_SERVER['HTTP_HOST']);
}

// Function to remove folders and files
function gltr_rrmdir($dir) {
	if (is_dir($dir)) {
		$files = scandir($dir);
		foreach ($files as $file)
			if ($file != "." && $file != "..") gltr_rrmdir("$dir/$file");
		rmdir($dir);
	}
	else if (file_exists($dir)) unlink($dir);
}

// Function to Copy folders and files
function gltr_rcopy($src, $dst) {

	if (file_exists ( $dst ))
		gltr_rrmdir ( $dst );
	if (is_dir ( $src )) {
		@mkdir ( $dst );
		$files = scandir ( $src );
		foreach ( $files as $file )
			if ($file != "." && $file != "..")
				gltr_rcopy ( "$src/$file", "$dst/$file" );
	} else if (file_exists ( $src ))
		@copy ( $src, $dst );
}


function gltr_rmove($src, $dest){
	_log ("========>dst=$dest  src=$src");
	gltr_rcopy($src, $dest);
	gltr_rrmdir($src);
	return true;
}


function gltr_move_to_stale($post_ID){
	wp_schedule_single_event( time() + 3, 'gltr_move_to_stale_scheduled', array( $post_ID ) );
}

add_action( 'gltr_move_to_stale_scheduled','gltr_move_to_stale_scheduled' );
function gltr_move_to_stale_scheduled($post_ID){
	$main_dir =  gltr_get_root_cache_dir().'/main';
	$stale_dir =  gltr_get_root_cache_dir().'/stale';
	$categories = get_the_category($post_ID);
	$tags = get_the_tags($post_ID);

	$tag_slugs = array();
	if (is_array($tags))
		foreach($tags as $tag) {
			$tag_slugs[] = strtolower($tag->slug);
		}
	
	$cat_slugs = array();
	foreach($categories as $category) {
		$cat_slugs[] = strtolower($category->slug);
	}
	
	//$patterns = array('/(category|tag|page|[0-9]{4})/');
	$options = get_option('gltr-options');
	foreach($options['preferred_languages'] as $lang) {
		//single
		$single_slug = substr(get_permalink($post_ID), strlen(home_url( '/' )));
		gltr_delete_outdated($single_slug, $lang);
		$src = $main_dir . '/' . $lang . '/'. $single_slug . '/index.html';
		if (file_exists($src)){
			$dst = $stale_dir . '/' . $lang . '/'. $single_slug;
			if (!is_dir($dst))
				wp_mkdir_p($dst);
			if (gltr_rmove($src,$dst)!==false)
				_log ("moved from $src to $dst ");
			else
				_log ("unable to move from $src to $dst ");
		}
				
		//home
		$src = $main_dir . '/' . $lang . '/index.html';
		gltr_delete_outdated('', $lang);
		if (file_exists($src)){
			$dst = $stale_dir . '/' . $lang;
			if (!is_dir($dst))
				wp_mkdir_p($dst);
			if (gltr_rmove($src,$dst)!==false)
				_log ("moved from $src to $dst ");
			else
				_log ("unable to move from $src to $dst ");
		}
		
		//all pages
		$src = $main_dir . '/' . $lang . '/page';
		gltr_delete_outdated('%/page/%', $lang);
		if (is_dir($src)){
			$dst = $stale_dir . '/' . $lang . '/page';
			if (!is_dir($dst))
				wp_mkdir_p($dst);
			if (gltr_rmove($src,$dst)!==false)
				_log ("moved from $src to $dst ");
			else
				_log ("unable to move from $src to $dst ");
		}
		
		//affected cats
		foreach($cat_slugs as $cat_slug){
			$src = $main_dir . '/' . $lang . '/category/' .  $cat_slug;
			gltr_delete_outdated('%/category/'. $cat_slug.'/%', $lang);
			if (is_dir($src)){
				$dst = $stale_dir . '/' . $lang . '/category/' .  $cat_slug;
				if (!is_dir($dst))
					wp_mkdir_p($dst);
			if (gltr_rmove($src,$dst)!==false)
					_log ("moved from $src to $dst ");
				else
					_log ("unable to move from $src to $dst ");
			}
		}
		
		//affected tags
		foreach($tag_slugs as $tag_slug){
			$src = $main_dir . '/' . $lang . '/tag/' .  $tag_slug;
			gltr_delete_outdated('%/tag/'. $tag_slug.'/%', $lang);
			if (is_dir($src)){
				$dst = $stale_dir . '/' . $lang . '/tag/' .  $tag_slug;
				if (!is_dir($dst))
					wp_mkdir_p($dst);
			if (gltr_rmove($src,$dst)!==false)
					_log ("moved from $src to $dst ");
				else
					_log ("unable to move from $src to $dst ");
			}
		}
		
		//todo archives
		
	}
}
/////////////////////////////////////////////////////////////////////////
function gltr_get_avail_transl($slug){
	$options = get_option('gltr-options');
	$avail_trs = array();
	foreach($options['preferred_languages'] as $l) {
		if (gltr_exists_translation($slug, $l)){
			$avail_trs[] = $l;
		}
	}
	sort($avail_trs);
	return 	$avail_trs;
}

function gltr_get_flags_bar($slug) {
	global $wp_query, $gltr_supported_langs;

	
	$options = get_option('gltr-options');
	$lang = $options['base_lang'];
	$avail_trs = gltr_get_avail_transl($slug);
	
	//$gltr_merged_image = gltr_get_root_cache_dir() . '/' . implode('',$avail_trs) . '.png';
	$gltr_merged_image = gltr_get_cache_flags_bar_path($slug,true);
	_log("gltr_merged_image=$gltr_merged_image; avail_transl=".implode($avail_trs));
	$num_cols = 9;
	$buf = '';

	$translations = $avail_trs;

	$transl_count = count($translations);

	$buf .= '<!--GLTR_BEGIN_WIDGET--><div id="translation_bar"><map id="gltr_flags_map" name="gltr_flags_map">';

	$curr_col = 0;
	$curr_row = 0;

	$dst_x = 0;
	$dst_y = 0;
	$map_left=0;
	$map_top=0;
	$map_right=16;
	$map_bottom=11;
	$grid;

	//filter preferred
	$preferred_transl = array();
	foreach ($gltr_supported_langs as $key => $value) {
		if ($key == $lang || in_array($key, $avail_trs))
			$preferred_transl[$key] = $value;
	}
	$num_rows=1;
	if ($num_cols > 0){
		$num_rows = (int)(count($preferred_transl)/$num_cols);
		if (count($preferred_transl)%$num_cols>0)$num_rows+=1;
	}
	if (!file_exists($gltr_merged_image)){
		$img_width = $num_cols*20;
		$img_height = $num_rows*15;
		$grid = imagecreatetruecolor ($img_width, $img_height);
		imagecolortransparent($grid, 000000);
	}
	_log($preferred_transl);
	foreach ($preferred_transl as $key => $value) {
		if ($curr_col >= $num_cols && $num_cols > 0) {
			$curr_col = 0;
			$dst_x = 0;
			$map_left = 0;
			$map_right = 16;
			$curr_row++;
		}
		$dst_y = $curr_row * 15;
		$map_top = $curr_row * 15;
		$map_bottom = $curr_row * 15 + 11;
		
		if ($key == $lang)
			$flg_url = htmlspecialchars(home_url( '/' ).$slug);
		else
			$flg_url = htmlspecialchars(home_url( '/' ).$key.'/'.$slug);
		
		$flg_image_url = gltr_get_flag_image_url($key);
		$flg_image_path = gltr_get_flag_image_path($key);
		_log("flg_image_path=$flg_image_path");

		$buf .="<area shape='rect' coords='$map_left,$map_top,$map_right,$map_bottom' href='$flg_url' id='flag_$key' $lnk_attr  alt='$value'/>";
		$map_left = $map_left+20;
		$map_right= $map_right+20;

		if ($num_cols > 0) $curr_col += 1;

		if (!file_exists($gltr_merged_image)){
			$img_tmp = @imagecreatefrompng($flg_image_path);

			imagecopymerge($grid, $img_tmp, $dst_x, $dst_y, 0, 0, 16, 11, 100);
			//gltr_debug("dst_x=$dst_x;dst_y=$dst_y;curr_row=$curr_row;curr_col=$curr_col;num_rows=$num_rows;flg_image_url=$flg_image_url");
			$dst_x = $dst_x + 20;
			@imagedestroy($img_tmp);
		}
	}//end foreach ($preferred_transl as $key => $value) {

	if (!file_exists($gltr_merged_image)){
		if (!is_writeable(dirname(__file__))){
			return "<b>Permission error: Please make your 'plugins/global-translator' directory writable by Wordpress</b>";
		} else {
			imagepng($grid, $gltr_merged_image);
			imagedestroy($grid);
		}
	}
	$merged_image_url=gltr_get_cache_flags_bar_url($slug);

	if ($num_cols == 0)
		$num_cols = count($translations);

	$buf .="</map>";
	$buf .= "<img style='border:0px;' src='$merged_image_url' usemap='#gltr_flags_map'/></div><!--GLTR_END_WIDGET-->";

	return $buf;
}


function gltr_debug($msg) {
	if (GLTR_DEBUG) {
		$today = date("Y-m-d H:i:s ");
		$myFile = dirname(__file__) . "/debug.log";
		$fh = fopen($myFile, 'a') or die("Can't open debug file. Please manually create the 'debug.log' file (inside the 'global-translator-pro' directory) and make it writable.");
		//$ua_simple = preg_replace("/(.*)\s\(.*/","\\1",$_SERVER['HTTP_USER_AGENT']);
		$ua_simple = "";
		//fwrite($fh, $today . " [from: ".$_SERVER['REMOTE_ADDR']."|$ua_simple] - [mem:" . memory_get_usage() . "] " . $msg . "\n");
		fwrite($fh, $today . " [from: ".$_SERVER['REMOTE_ADDR']."|$ua_simple] - " . $msg . "\n");
		fclose($fh);
	}
}

if(!function_exists('_log')){
	function _log( $message ) {
		if( is_array( $message ) || is_object( $message ) ){
			$message = gltr_get_slug().' - '.trim(str_replace(PHP_EOL, ' ', print_r( $message, true )));
		} else {
			$message = gltr_get_slug().' - '.trim(str_replace(PHP_EOL, ' ', $message));
		}
		gltr_debug( $message );
	} 
}
/////////////////////////////////////////////////////////////////////////
function gltr_get_flag_image_url($language) {
	return plugin_dir_url( __FILE__ ). 'flags/' . $language . '.png';
}
function gltr_get_flag_image_path($language) {
	return plugin_dir_path( __FILE__ ). 'flags/' . $language . '.png';
}
/////////////////////////////////////////////////////////////////////////

require_once(dirname(__FILE__).'/admin.php');
?>