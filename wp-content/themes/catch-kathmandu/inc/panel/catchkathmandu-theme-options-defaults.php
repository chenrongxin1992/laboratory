<?php
/**
 * @package Catch Themes
 * @subpackage Catch_Kathmandu
 * @since Catch Kathmandu 1.0
 */

/**
 * Set the default values for all the settings. If no user-defined values
 * is available for any setting, these defaults will be used.
 */
global $catchkathmandu_options_defaults;
$catchkathmandu_options_defaults = array(
	'disable_responsive'					=> '0',
	'enable_menus'							=> '1',
	'homepage_headline'						=> 'Catch Kathmandu is a Responsive WordPress Theme',
	'homepage_subheadline'					=> 'It is Simple, Clean and Responsive WordPress Theme which automatically adapts to the screen\'s size.',
	'homepage_headline_button'				=> 'Reviews',
	'homepage_headline_url'					=> esc_url( 'http://wordpress.org/support/view/theme-reviews/catch-kathmandu' ),
	'reset_featured_image'					=> '2',
	'featured_header_image'					=> get_template_directory_uri().'/images/demo/header-image-1280x400.jpg',
	'enable_featured_header_image'			=> 'disable',
	'page_featured_image'					=> 'full',
	'featured_header_image_url'				=> '',
	'featured_header_image_alt'				=> '',
	'featured_header_image_base'			=> '0',
 	'disable_header_right_sidebar'			=> '0',
	'color_scheme'							=> 'light',
	'reset_typography'						=> '2',
	'custom_css'							=> '',
	'disable_scrollup'						=> '0',
	'sidebar_layout'						=> 'right-sidebar',
	'content_layout'						=> 'full',
	'featured_image'						=> 'featured',
	'reset_layout'							=> '2',
	'more_tag_text'							=> 'Continue Reading &rarr;',
	'reset_moretag'							=> '2',
	'excerpt_length'						=> 30,
 	'search_display_text'					=> 'Search &hellip;',
	'feed_url'								=> '',
	'disable_homepage_headline'				=> '0',
	'disable_homepage_subheadline'			=> '0',
	'disable_homepage_button'				=> '0',
	'disable_homepage_featured'				=> '0',
	'homepage_featured_headline'			=> '',
	'homepage_featured_qty'					=> 4,
	'homepage_featured_layout'				=> 'four-columns',
	'homepage_featured_image'				=> array(),
	'homepage_featured_url'					=> array(),
	'homepage_featured_base'				=> array(),
	'homepage_featured_title'				=> array(),
	'homepage_featured_content'				=> array(),
	'enable_posts_home'						=> '1',
	'move_posts_home'						=> '0',
 	'front_page_category'					=> '0',
	'select_slider_type'					=> 'demo-slider',
	'enable_slider'							=> 'enable-slider-homepage',
 	'featured_slider'						=> array(),
	'featured_slider_page'					=> array(),
	'slider_category'						=> '0',
	'featured_image_slider_image'			=> array(),
	'featured_image_slider_link' 			=> array(),
	'featured_image_slider_base'			=> array(),
	'featured_image_slider_title' 			=> array(),
	'featured_image_slider_content' 		=> array(),
	'slider_qty'							=> 4,
 	'transition_effect'						=> 'fade',
 	'transition_delay'						=> 4,
 	'transition_duration'					=> 1,
	'exclude_slider_post'					=> 0,
 	'social_facebook'						=> '',
 	'social_twitter'						=> '',
 	'social_googleplus'						=> '',
 	'social_pinterest'						=> '',
 	'social_youtube'						=> '',
 	'social_vimeo'							=> '',
 	'social_linkedin'						=> '',
 	'social_slideshare'						=> '',
 	'social_foursquare'						=> '',
 	'social_flickr'							=> '',
 	'social_tumblr'							=> '',
 	'social_deviantart'						=> '',
 	'social_dribbble'						=> '',
 	'social_myspace'						=> '',
 	'social_wordpress'						=> '',
 	'social_rss'							=> '',
 	'social_delicious'						=> '',
 	'social_lastfm'							=> '',
	'social_instagram'						=> '',
	'social_github'							=> '',
	'social_vkontakte'						=> '',
	'social_myworld'						=> '',
	'social_odnoklassniki'					=> '',
	'social_goodreads'						=> '',
	'social_skype'							=> '',
	'social_soundcloud'						=> '',
	'social_email'							=> '',
	'social_contact'						=> '',
	'social_xing'							=> '',
	'social_meetup'							=> '',
 	'analytic_header'						=> '',
 	'analytic_footer'						=> '',
	'footer_code'							=> '<div class="copyright">'. esc_attr__( 'Copyright', 'catch-kathmandu' ) . ' &copy; ' . catchkathmandu_the_year() . '&nbsp;' . catchkathmandu_site_link() . '&nbsp;' . esc_attr__( 'All Rights Reserved', 'catch-kathmandu' ) . '.</div><div class="powered">'. esc_attr__( 'Catch Kathmandu by', 'catch-kathmandu' ) . '&nbsp;' . catchkathmandu_shop_link() . '</div>',
	'reset_footer'							=> '2'
);
global $catchkathmandu_options_settings;
$catchkathmandu_options_settings = catchkathmandu_options_set_defaults( $catchkathmandu_options_defaults );

function catchkathmandu_options_set_defaults( $catchkathmandu_options_defaults ) {
	$catchkathmandu_options_settings = array_merge( $catchkathmandu_options_defaults, (array) get_option( 'catchkathmandu_options', array() ) );
	return $catchkathmandu_options_settings;
}

/**
 * Returns the current year.
 *
 * @uses date() Gets the current year.
 * @return string
 */
function catchkathmandu_the_year() {
	return esc_attr( date_i18n( __( 'Y', 'catch-kathmandu' ) ) );
}


/**
 * Returns a link back to the site.
 *
 * @uses get_bloginfo() Gets the site link
 * @return string
 */
function catchkathmandu_site_link() {
	return '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" ><span>' . get_bloginfo( 'name', 'display' ) . '</span></a>';
}


/**
 * Returns a link to Theme Shop.
 *
 * @return string
 */
function catchkathmandu_shop_link() {
	return '<a href="'. esc_url( __( 'https://catchthemes.com', 'catch-kathmandu' ) ) . '" target="_blank" title="' . esc_attr__( 'Catch Themes', 'catch-kathmandu' ) . '"><span>' . __( 'Catch Themes', 'catch-kathmandu' ) . '</span></a>';
}


/**
 * Returns an array of color schemes registered for catchkathmandu.
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_color_schemes() {
	$options = array(
		'light' 		=> __( 'Light (Blue)', 'catch-kathmandu' ),
		'dark'			=> __( 'Dark', 'catch-kathmandu' ),
		'lightblack'	=> __( 'Light (Black)', 'catch-kathmandu' ),
	);

	return apply_filters( 'catchkathmandu_color_schemes', $options );
}


/**
 * Returns an array of featured content layout options
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_featured_content_layouts() {
	$options = array(
		'three-columns' => __( '3 Columns', 'catch-kathmandu' ),
		'four-columns'	=> __( '4 Columns', 'catch-kathmandu' ),
	);

	return apply_filters( 'catchkathmandu_featured_content_layouts', $options );
}


/**
 * Returns an array of enable header image options
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_enable_header_featured_image() {
	$options = array(
		'homepage' 		=> __( 'Homepage', 'catch-kathmandu' ),
		'excludehome' 	=> __( 'Excluding Homepage', 'catch-kathmandu' ),
		'allpage' 		=> __( 'Entire Site', 'catch-kathmandu' ),
		'postpage' 		=> __( 'Entire Site, Page/Post Featured Image', 'catch-kathmandu' ),
		'pagespostes'	=> __( 'Pages & Posts', 'catch-kathmandu' ),
		'disable'		=> __( 'Disable', 'catch-kathmandu' ),
	);

	return apply_filters( 'catchkathmandu_enable_header_featured_image', $options );
}


/**
 * Returns an array of page/post featured image size
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_page_post_featured_image_size() {
	$options = array(
		'full' 		=> __( 'Full Image', 'catch-kathmandu' ),
		'slider' 	=> __( 'Slider Image', 'catch-kathmandu' ),
		'featured'	=> __( 'Featured Image', 'catch-kathmandu' ),
	);

	return apply_filters( 'catchkathmandu_page_post_featured_image_size', $options );
}


/**
 * Returns an array of content featured image size
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_content_featured_image_size() {
	$options = array(
		'full' 		=> __( 'Full Image', 'catch-kathmandu' ),
		'slider' 	=> __( 'Slider Image', 'catch-kathmandu' ),
		'featured'	=> __( 'Featured Image', 'catch-kathmandu' ),
		'disable'	=> __( 'Disable Image', 'catch-kathmandu' ),
	);

	return apply_filters( 'catchkathmandu_content_featured_image_size', $options );
}


/**
 * Returns an array of sidebar layout options
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_sidebar_layout_options() {
	$options = array(
		'right-sidebar' => __( 'Right Sidebar', 'catch-kathmandu' ),
		'left-sidebar' 	=> __( 'Left Sidebar', 'catch-kathmandu' ),
		'no-sidebar'	=> __( 'No Sidebar', 'catch-kathmandu' ),
	);

	return apply_filters( 'catchkathmandu_sidebar_layout_options', $options );
}


/**
 * Returns an array of content layout options
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_content_layout_options() {
	$options = array(
		'full' 		=> __( 'Full Content Display', 'catch-kathmandu' ),
		'excerpt' 	=> __( 'Excerpt/Blog Display', 'catch-kathmandu' ),
	);

	return apply_filters( 'catchkathmandu_content_layout_options', $options );
}


/**
 * Returns an array of slider types
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_slider_types() {
	$options = array(
		'demo-slider' 		=> __( 'Demo Slider', 'catch-kathmandu' ),
		'post-slider' 		=> __( 'Post Slider', 'catch-kathmandu' ),
		'category-slider' 	=> __( 'Category Slider', 'catch-kathmandu' ),
	);

	return apply_filters( 'catchkathmandu_slider_types', $options );
}


/**
 * Returns an array of slider enable options
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_enable_slider_options() {
	$options = array(
		'enable-slider-homepage'=> __( 'Homepage', 'catch-kathmandu' ),
		'enable-slider-allpage' => __( 'Entire Site', 'catch-kathmandu' ),
		'disable-slider' 		=> __( 'Disable', 'catch-kathmandu' ),
	);

	return apply_filters( 'catchkathmandu_enable_slider_options', $options );
}


/**
 * Returns an array of slider transition effects
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_transition_effects() {
	$options = array(
		'fade'			=> __( 'fade', 'catch-kathmandu' ),
		'wipe' 			=> __( 'wipe', 'catch-kathmandu' ),
		'scrollUp' 		=> __( 'scrollUp', 'catch-kathmandu' ),
		'scrollDown'	=> __( 'scrollDown', 'catch-kathmandu' ),
		'scrollUp' 		=> __( 'scrollUp', 'catch-kathmandu' ),
		'scrollLeft'	=> __( 'scrollLeft', 'catch-kathmandu' ),
		'scrollRight'	=> __( 'scrollRight', 'catch-kathmandu' ),
		'blindX' 		=> __( 'blindX', 'catch-kathmandu' ),
		'blindY' 		=> __( 'blindY', 'catch-kathmandu' ),
		'blindZ' 		=> __( 'blindZ', 'catch-kathmandu' ),
		'cover' 		=> __( 'cover', 'catch-kathmandu' ),
		'shuffle' 		=> __( 'shuffle', 'catch-kathmandu' ),
	);

	return apply_filters( 'catchkathmandu_transition_effects', $options );
}