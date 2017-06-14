<?php 
add_action( 'admin_init', 'gltr_admin_init' );
function gltr_admin_init() {
	register_setting( 'gltr-plugin-settings-group', 'gltr-options', 'gltr_options_validate' );
}

/////////////////////////////////////////////////////////////////////////

add_action( 'admin_enqueue_scripts', 'gltr_admin_enqueued_assets' );
function gltr_admin_enqueued_assets() {
	wp_enqueue_style( 'gltr_admin_css',plugin_dir_url( __FILE__ ) . '/admin.css'  );
}

/////////////////////////////////////////////////////////////////////////

add_action('admin_menu', 'gltr_plugin_menu');
function gltr_plugin_menu() {
	_log('gltr_plugin_menu::begin');
	add_menu_page('Global Translator', 'Global Translator', 'administrator', 'gltr-plugin-configuration', 'gltr_plugin_settings_page', plugin_dir_url( __FILE__ ).'assets/icon-16x16.png');
	add_submenu_page( 'gltr-plugin-configuration', 'Main configuration', 'Main configuration', 'administrator', 'gltr-plugin-configuration', 'gltr_plugin_settings_page');
	add_submenu_page( 'gltr-plugin-configuration', 'Statistics', 'Statistics', 'administrator', 'gltr-plugin-stats', 'gltr_plugin_stats_page');
	_log('gltr_plugin_menu::end');
}

/////////////////////////////////////////////////////////////////////////

add_filter( 'plugin_action_links', 'gltr_plugin_actions', 10, 2 );
function gltr_plugin_actions( $links, $file ) {
	if( $file == 'global-translator/global-translator.php' && function_exists( "admin_url" ) ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=gltr-plugin-configuration' ) . '">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}

add_action( 'admin_notices', 'gltr_admin_notice');
function gltr_admin_notice() {
	$options = get_option('gltr-options');
	if( gltr_get_key() === false && substr( $_SERVER["PHP_SELF"], -11 ) == 'plugins.php' && function_exists( "admin_url" ) )
		echo '<div class="error"><p><strong>' . sprintf( 'Global Translator needs to be configured. Please go to the <a href="%s">plugin admin page</a> to activate it.',
				admin_url( 'admin.php?page=gltr-plugin-configuration' ) ) . '</strong></p></div>';
}


/////////////////////////////////////////////////////////////////////////

function gltr_common_header(){
	$options = get_option('gltr-options');
	if (!is_writable(WP_CONTENT_DIR)){
		echo '<div class="error fade" ><ul><li>Please make the <strong>'.WP_CONTENT_DIR.'</strong> directory writable, otherwise the plugin will not work.</li></ul></div>';
	}
	if (strlen($options['messages']) > 0){
		echo '<div class="error fade" ><ul>' . $options['messages'] .'</ul></div>';
	}
	if ( '' == get_option( 'permalink_structure' ) ) {
		echo '<div id="message" class="updated fade"><h3>' . __( 'Permalink Structure Error', 'gltr_domain' ) . '</h3>';
		echo "<p>" . __( 'A custom url or permalink structure is required for this plugin to work correctly. Please go to the <a href="options-permalink.php">Permalinks Options Page</a> to configure your permalinks.' ) . "</p>";
		echo '</div>';
	}	
	?> 
	<?php 
}



function gltr_plugin_stats_page(){
	$options = get_option('gltr-options');
	gltr_common_header();
	$rows = gltr_get_keys_data();
	?>
	<div class="wrap">
	<h2><?php _e( 'Global Translator: API usage and Statistics', 'gltr_text_domain' ) ?></h2> 
	<p></p>
	<h3><?php _e( 'API Keys status', 'gltr_text_domain' ) ?></h3>
	<p></p>
	<?php 
	if (count($rows) == 0){
	?>
	<h3><?php _e('Still no keys registered ', 'gltr_text_domain');?></h3>
	<?php 
	}else{
	?>
	<table class="widefat" cellspacing="0">
	    <thead>
	    <tr>
	            <th id="columnname" class="manage-column column-columnkey" scope="col">Key</th>
	            <th id="columnname" class="manage-column column-columdate" scope="col">Creation date</th>
	            <th id="columnname" class="manage-column column-columdate" scope="col">Last translation date</th>
	            <th id="columnname" class="manage-column column-columnnum" scope="col">Total API calls</th> 
	            <th id="columnname" class="manage-column column-columnnum" scope="col">Total translated chars</th> 	            
	            <th id="columnname" class="manage-column column-columnstatus" scope="col">Current status</th>
	    </tr>
	    </thead>
	    <tbody>
	    <?php 
	    $alt = false;
	    foreach ($rows as $row){
	    	$alt = !$alt;		
	    ?>
	        <tr <?php echo ($alt?'class="alternate"':'');?> valign="top">
	            <td class="column-columnkey">
	            	<?php echo $row->apikey;?>
	                <div class="row-actions">
	                    <!--<span><a href="<?php echo admin_url( 'admin.php' ).'?action=gltr_delete_key&id='.$row->id; ?>">Delete</a> </span>
	                     |<span><a href="#">Detailed stats</a></span> -->
	                </div>
	            </td>
	            <td class="column-columdate"><?php echo $row->creation_time;?></td>
	            <td class="column-columdate"><?php echo $row->last_succ_response_time;?></td>
	            <td class="column-columnnum"><?php echo $row->total_succ_api_calls;?></td>
	            <td class="column-columnnum"><?php echo $row->total_succ_translated_chars;?></td>
	            <td class="column-columnstatus"><?php 
	            $status = 'blocked';
	            $tooltip = 'Blocked Key';
	            if ($row->last_response_code=='200'){
	            	$tooltip = 'Active Key';
	            	$status = 'working';
	            }else if ($row->last_response_code=='403'){
	            	$tooltip = 'Temporarily suspended Key';
	            	$status = 'paused';
	            }
	            ?>
	            <img title="<?php echo $tooltip;?>" alt="<?php echo $tooltip;?>" src="<?php echo plugin_dir_url( __FILE__ ).'assets/transparent.png'?>" class="gltr_sprite gltr_<?php echo $status;?> gltr_icon-small" />
	            </td>
	            </tr>
	    <?php }?>
	    </tbody>
	</table>
	<?php }?>
	<p></p>
	<h3><?php _e( 'Enqueued translations:', 'gltr_text_domain' ) ?> <?php echo gltr_count_enqueued_pages()?></h3>
	<p></p>
	<h3><?php _e( 'Latest 10 successful translations', 'gltr_text_domain' ) ?></h3>
	<?php 
	if (count($options['recent_translations']) == 0){
	?>
	<p><?php _e( 'Still not available translations', 'gltr_text_domain' ) ?></p>
	<?php 
	} else {
	?>
	<ol>
	<?php 
	foreach ($options['recent_translations'] as $link){
		$link = '/'.$link;
		?>
		<li><a target="_blank" href="<?php echo $link?>"><?php echo $link?></a></li>
	<?php 
	}
	?>
	</ol>
	<?php 
	}
	?>
	
	</div>
<?php 
}

add_action( 'admin_action_gltr_delete_key', 'gltr_delete_key_action' );
function gltr_delete_key_action(){
	_log("deleting key ...");
	if (isset($_GET['id'])){
		$id = intval($_GET['id']);
		gltr_delete_key($id);
		_log("..done");
	}
	wp_redirect( $_SERVER['HTTP_REFERER'] );
	exit();
}
/////////////////////////////////////////////////////////////////////////

function gltr_plugin_guide_page() {
	gltr_common_header();
	?>
	<style>
	.gltr_help p {
		font-size:11pt;
		margin: 1.5em 1.5em;
	}
	</style>
	<div class="wrap">
	<h2><?php _e( 'Global Translator: Getting started', 'gltr_text_domain' ) ?></h2> 

</div>
<?php
}

/////////////////////////////////////////////////////////////////////////

function gltr_plugin_settings_page() {
	global $gltr_supported_langs;
	_log('gltr_plugin_settings_page::begin');
	$options = get_option('gltr-options');
	gltr_common_header();
	?>
	<div class="wrap">
	<h2><?php _e( 'Global Translator: Main configuration', 'gltr_text_domain' ) ?></h2> 

	<form method="post" action="options.php">
	<?php settings_fields( 'gltr-plugin-settings-group' ); ?>
	<?php do_settings_sections( 'gltr-plugin-settings-group' ); ?>
	
	<h3><?php _e( 'Engine Settings', 'gltr_text_domain' ) ?></h3>
	<table class="form-table">
	<tr valign="top">
	<th scope="row"><?php _e( 'Enable translation engine', 'gltr_text_domain' ) ?><br><small></small></th>
	<td>
	<input name="gltr-options[engine_enabled]" type="checkbox" value="1" <?php checked( '1', $options['engine_enabled'] ); ?>>
	<span class="description"><?php _e( 'When enabled it will try to translate a page every 4 minutes (if you have almost one active API key); when disabled only the already translated pages will be served.', 'gltr_text_domain' ) ?></span>
	</td>
	</table>
	
	<h3><?php _e( 'API Settings', 'gltr_text_domain' ) ?></h3>
	<table class="form-table">
	<tr valign="top">
	<th scope="row"><?php _e( 'Your Yandex Translate API keys', 'gltr_text_domain' ) ?>
	<br><small></small></th>
	<td>
	<?php 
	$rows = gltr_get_keys_data();
	foreach ($rows as $row){?>
		<p><?php echo $row->apikey; ?>&nbsp;<span>(<a href="<?php echo admin_url( 'admin.php' ).'?action=gltr_delete_key&id='.$row->id; ?>">Delete</a>)</span></p>
	<?php }
	if (count($rows)==0){
		echo "<p>Still not registered Yandex Translate API keys. One or more API Keys are needed in order to enable the translation engine.</p>";
	}
	
	?>
	<br>
	<span class="description"><?php _e( '<a target="_blank" href="https://tech.yandex.com/keys/get/?service=trnsl">Click here</a> for obtaining one or more keys for free. <br/>After the translation of about 1M characters a Yandex key is usually temporarily suspended: just add more keys in order to increase this limit (the plugin will automatically optimize their usage).', 'gltr_text_domain' ) ?></span>
	</td>
	<tr valign="top">
	<th scope="row"><?php _e( 'Add a new API Key:', 'gltr_text_domain' ) ?></th>
	<td>
	<input placeholder="trnsl" type="text" name="gltr-options[api-keys]" size="100"/>
	</td>
	</tr>
	</table>

	<h3><?php _e( 'Translations Settings', 'gltr_text_domain' ) ?></h3>
	
	<table class="form-table">
	<tr valign="top">
	<th scope="row"><?php _e( 'Enable credits link.', 'gltr_text_domain' ) ?><br><small></small></th>
	<td><input name="gltr-options[show_credits]" type="checkbox" value="1" <?php checked( '1', $options['show_credits'] ); ?>>
	<span class="description"><?php _e( 'Please note that the credits link is required by the <a target="_blank" href="https://tech.yandex.com/translate/doc/dg/concepts/design-requirements-docpage/">Yandex Translate TOS</a>.', 'gltr_text_domain' ) ?></span>
	</td>
	</tr>
	<tr valign="top">
	<th scope="row"><?php _e( 'Your site language', 'gltr_text_domain' );?></th>
	<td><select name="gltr-options[base_lang]">
	<?php foreach($gltr_supported_langs as $code=>$desc){
		echo "<option ".(esc_attr( $options['base_lang'] ) == $code?" selected ":"")." value='$code'>".$desc."</option>";
	}?>
	</select></td>
	</tr>
	<tr valign="top">
	<th scope="row"><?php _e( 'Translations to enable', 'gltr_text_domain' ) ?></th>
	<td>
        <ul style="list-style-image: none; list-style-position:outside; list-style-type:none;">
        <?php    
        $i=0;
        //print_r($options['preferred_languages']);
        $gltr_preferred_languages = $options['preferred_languages'];
        foreach($gltr_supported_langs as $lang_key=>$lang_value){
           
              if ($options['base_lang'] == $lang_key) continue;
              $chk_val = "";
              if ( in_array($lang_key, $gltr_preferred_languages) ) 
                $chk_val = "checked";
              echo '<li style="float:left;width:30%;"><input type="checkbox" name="gltr-options[preferred_languages][' . $i . ']" ' . $chk_val . ' value="' . $lang_key . '">
              <img src="' . gltr_get_flag_image_url($lang_key) . '"/>' . $lang_value . '&nbsp;(<strong>'.$lang_key.'</strong>)</li>';
              $i++;
        }
        ?>
        </ul>
	</td>
	</tr>
	</table>

	<?php submit_button(); ?>


	</form>
	</div>
	<?
	_log('gltr_plugin_settings_page::end');
}

/////////////////////////////////////////////////////////////////////////

function gltr_options_validate($options){
	_log('gltr_options_validate::begin');
	$new_options = array();
	$prev_options = get_option('gltr-options');
	$options['messages'] = '';
    $messages = '';


    
    if (isset($options['api-keys'])){
    	if (strlen($options['api-keys']) == 0 && gltr_get_key() === false)
    		$messages .= "You need one or more <a target='_blank' href='https://tech.yandex.com/keys/get/?service=trnsl'>Yandex Translation API keys</a> in order to enable the translations<br/>";
    	else{
	    	$keys=explode("\n",$options['api-keys']);
	    	foreach ($keys as $key){
	    		$key = trim($key);
	    		if (strlen($key)>0)
	    			gltr_add_key($key);
	    	}
    	}
    }
    
    if (!isset($options['engine_enabled'])){
    	$options['engine_enabled'] = '0';
    }

    if (isset($options['preferred_languages'])){
		if (!is_array($options['preferred_languages']))
			$messages .= "Unable to save preferred languages<br/>";
		else if (count($options['preferred_languages'])==0)
			$messages .= "You should select almost one target language<br/>";
		else{
			//$options['preferred_languages'] = array_unique(array_merge($prev_options['preferred_languages'],$options['preferred_languages']));
		}
    }else{
    	$messages .= "You should select almost one target language<br/>";
    }
    
    if (strlen($messages) > 0){
        $options['messages'] = $messages;
    }else{
		//success
    }

    $options = array_merge((array)$prev_options, (array)$options);

    global $wp_rewrite;
    $wp_rewrite->flush_rules( false );
    gltr_clean_unneeded_langs($options);
        
    _log('gltr_options_validate::end');

    return $options;
}


?>