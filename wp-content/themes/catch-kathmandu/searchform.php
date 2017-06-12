<?php
/**
 * The template for displaying search forms in Catch Kathmandu
 *
 * @package Catch Themes
 * @subpackage Catch Kathmandu
 * @since Catch Kathmandu 1.0
 */
 
// get the data value from theme options
global $catchkathmandu_options_settings;
$options = $catchkathmandu_options_settings;
?>
	<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label for="s" class="assistive-text"><?php _e( 'Search', 'catch-kathmandu' ); ?></label>
		<input type="text" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php echo esc_attr( $options['search_display_text'] ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'catch-kathmandu' ); ?>" />
	</form>