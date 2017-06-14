<?php 
$gltr_ob_started = false;
$gltr_supported_langs=array(
'af'=>'Afrikaans',
'sq'=>'Albanian',
'ar'=>'Arabian',
'hy'=>'Armenian',
'az'=>'Azeri',
'eu'=>'Basque',
'be'=>'Belarusian',
'bs'=>'Bosnian',
'bg'=>'Bulgarian',
'ca'=>'Catalan',
'hr'=>'Croatian',
'cs'=>'Czech',
'zh'=>'Chinese',
'da'=>'Danish',
'nl'=>'Dutch',
'en'=>'English',
'et'=>'Estonian',
'fi'=>'Finnish',
'fr'=>'French',
'gl'=>'Galician',
'ka'=>'Georgian',
'de'=>'German',
'el'=>'Greek',
'ht'=>'Haitian (Creole)',
'he'=>'Hebrew',
'hu'=>'Hungarian',
'is'=>'Icelandic',
'id'=>'Indonesian',
'ga'=>'Irish',
'it'=>'Italian',
'ja'=>'Japanese',
'kk'=>'Kazakh',
'ko'=>'Korean',
'ky'=>'Kyrgyz',
'la'=>'Latin',
'lv'=>'Latvian',
'lt'=>'Lithuanian',
'mk'=>'Macedonian',
'mg'=>'Malagasy',
'ms'=>'Malay',
'mt'=>'Maltese',
'mn'=>'Mongolian',
'no'=>'Norwegian',
'fa'=>'Persian',
'pl'=>'Polish',
'pt'=>'Portuguese',
'ro'=>'Romanian',
'ru'=>'Russian',
'es'=>'Spanish',
'sr'=>'Serbian',
'sk'=>'Slovak',
'sl'=>'Slovenian',
'sw'=>'Swahili',
'sv'=>'Swedish',
'tl'=>'Tagalog',
'tg'=>'Tajik',
'tt'=>'Tatar',
'th'=>'Thai',
'tr'=>'Turkish',
'uz'=>'Uzbek',
'uk'=>'Ukrainian',
'vi'=>'Vietnamese',
'cy'=>'Welsh'); 

function gltr_get_keys_data(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'gltr_keys';
	$rows = $wpdb->get_results( "SELECT id,apikey,creation_time,last_succ_response_time,last_response_time,last_response_code,total_succ_api_calls,total_succ_translated_chars FROM $table_name order by creation_time desc" );
	return $rows;
}

function gltr_get_key(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'gltr_keys';
	$rows = $wpdb->get_results( "SELECT apikey FROM $table_name where last_response_code is null or last_response_code = '200' order by last_succ_response_time asc" );
	if (count($rows)==0)
		$rows = $wpdb->get_results( "SELECT apikey FROM $table_name where last_response_code is not null and last_response_code <> '200' order by last_response_time asc" );
	if (count($rows)==0){
		_log("no available keys");
		return false;
	}else{
		//_log("returning key: ".$rows[0]->apikey);
		return $rows[0]->apikey;
	}
}

function gltr_add_key($key){
	global $wpdb;
	$table_name = $wpdb->prefix . 'gltr_keys';
	$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM $table_name where apikey = %s",$key));
	if ($count == 0){
		$wpdb->insert(
				$table_name,
				array(
					'apikey' => $key,
					'creation_time' => current_time( 'mysql' )
				)
		);
	}
}

function gltr_delete_key($id){
	global $wpdb;
	$table_name = $wpdb->prefix . 'gltr_keys';
	$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(id) FROM $table_name where id = %d",$id));
	if ($count > 0){
		$wpdb->query($wpdb->prepare("DELETE FROM $table_name where id = %d",$id));
	}
}

function gltr_update_key($key, $response_code, $chars_num=0){
	global $wpdb;
	$table_name = $wpdb->prefix . 'gltr_keys';
	$rows = $wpdb->get_results( "SELECT total_succ_api_calls,total_succ_translated_chars FROM $table_name where apikey = '$key'" );
	if (count($rows) == 1){
		$fields_to_update = array(
				'last_response_time' => current_time( 'mysql' ),
				'last_response_code' =>  $response_code
		);
		if ($response_code == '200'){
			$fields_to_update['total_succ_api_calls'] = $rows[0]->total_succ_api_calls + 1;
			$fields_to_update['total_succ_translated_chars'] = $rows[0]->total_succ_translated_chars + $chars_num;
			$fields_to_update['last_succ_response_time'] = current_time( 'mysql' );
		}
		$wpdb->update(
				$table_name,
				$fields_to_update,
				array(
						'apikey' => $key
				)
		);
	}
}

/**
 * 
 * 
 * @param unknown $slug
 * @param unknown $content
 * @param unknown $lang
 * @param number $priority: 0 => HIGHEST (home page); 1 => single pages/posts
 * @return boolean
 */
function gltr_enqueue_page($slug,$content,$lang,$priority=10){
	global $wpdb;
	$options = get_option('gltr-options');
	$table_name = $wpdb->prefix . 'gltr_queue';
	$count = $wpdb->get_var($wpdb->prepare("select count(id) from wp_gltr_queue where slug=%s and lang=%s",$slug,$lang));
	if ($count == 0){
		_log("Enqueuing translation req $slug,$lang ".strlen($slug)." ".strlen($lang));
		return $wpdb->insert(
				$table_name,
				array(
						'slug' => $slug,
						'content' => $content,
						'lang' => $lang,
						'creation_time' => current_time( 'mysql' ),
						'priority' => $priority
				)
		);
	}else{
		return false;	
	}
}

function gltr_clean_unneeded_langs($options){
	global $wpdb;
	$options = get_option('gltr-options');
	$table_name = $wpdb->prefix . 'gltr_queue';
	if (is_array($options['preferred_languages']) && count($options['preferred_languages'])>0){
		$needed_langs = "'".implode("','",$options['preferred_languages'])."'";
		$wpdb->query("DELETE FROM $table_name where lang not in ($needed_langs)");
	}
}

function gltr_get_next_page(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'gltr_queue';
	$rows = $wpdb->get_results( "SELECT slug,content,lang FROM $table_name order by priority, creation_time asc limit 1" );
	if (count($rows)==0){
		return false;
	}else{
		return $rows[0];
	}
}

function gltr_count_enqueued_pages(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'gltr_queue';
	$count = $wpdb->get_var("SELECT count(*) FROM $table_name");
	return $count;
}

function gltr_confirm_page_translation($slug,$lang,$file_created=false){
	global $wpdb;
	$table_name = $wpdb->prefix . 'gltr_queue';
	$wpdb->delete(
		$table_name,
		array(
			'slug' => $slug,
			'lang' => $lang
		)
	);
	if ($file_created){
		$options = get_option('gltr-options');
		if (!is_array($options['recent_translations']))
			$options['recent_translations'] = array();
		while (count($options['recent_translations'])>=10)
			array_pop($options['recent_translations']);
		$new_item = $lang . '/' . $slug;
		if (!in_array($new_item, $options['recent_translations']))
			array_unshift($options['recent_translations'], $new_item);
		update_option("gltr-options",$options);
	}
}

function gltr_delete_outdated($slug_pattern,$lang){
	global $wpdb;
	$table_name = $wpdb->prefix . 'gltr_queue';
	if (strpos($slug_pattern,'%')!==false)
		$q = "delete from $table_name where slug like '$slug_pattern' and lang='$lang'";
	else
		$q = "delete from $table_name where slug = '$slug_pattern' and lang='$lang'";
	_log("gltr_delete_outdated:: $q");
	$wpdb->query($q);
}

?>