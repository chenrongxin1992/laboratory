<?php
/**
 * Catch Kathmandu Pro functions and definitions
 *
 * @package Catch Themes
 * @subpackage Catch Kathmandu
 * @since Catch Kathmandu 1.0
 */


if ( ! function_exists( 'catchkathmandu_content_width' ) ) :
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	function catchkathmandu_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'catchkathmandu_content_width', 750 );
	}
endif; // catchkathmandu_content_width
add_action( 'after_setup_theme', 'catchkathmandu_content_width', 0 );


if ( ! function_exists( 'catchkathmandu_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Catch Kathmandu 1.0
 */
function catchkathmandu_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Catch Kathmandu, use a find and replace
	 * to change 'catch-kathmandu' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'catch-kathmandu', get_template_directory() . '/languages' );

	/**
	 * Add callback for custom TinyMCE editor stylesheets. (editor-style.css)
	 * @see http://codex.wordpress.org/Function_Reference/add_editor_style
	 */
	add_editor_style();

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/*
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support( 'title-tag' );

	/**
	 * Theme Options Defaults
	 */
	require( get_template_directory() . '/inc/panel/catchkathmandu-theme-options-defaults.php' );

	/**
	 * Custom Theme Options
	 */
	require( get_template_directory() . '/inc/panel/theme-options.php' );

	/**
	 * Customizer Options
	 */
	require( get_template_directory() . '/inc/panel/customizer/customizer.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/catchkathmandu-functions.php' );

	/**
	 * Metabox
	 */
	require( get_template_directory() . '/inc/catchkathmandu-metabox.php' );

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Register Sidebar and Widget.
	 */
	require( get_template_directory() . '/inc/catchkathmandu-widgets.php' );

	/*
	 * This theme supports custom background color and image, and here
	 *
	 */
	if ( function_exists( 'get_custom_header') ) {
		//add_theme_support( 'custom-background' );
		add_theme_support( 'custom-background', array( 'wp-head-callback' => 'catchkathmandu_background_callback' ) );
	}

	/**
     * This feature enables custom-menus support for a theme.
     * @see http://codex.wordpress.org/Function_Reference/register_nav_menus
     */
	register_nav_menus(array(
		'primary' 	=> __( 'Primary Menu', 'catch-kathmandu' ),
	   	'secondary'	=> __( 'Secondary Menu', 'catch-kathmandu' )
	) );

	/**
	 * Custom Menus Functions.
	 */
	require( get_template_directory() . '/inc/catchkathmandu-menus.php' );

	/**
	 * Add support for the Aside Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' ) );

	/**
     * This feature enables Jetpack plugin Infinite Scroll
     */
    add_theme_support( 'infinite-scroll', array(
		'type'           => 'click',
        'container'      => 'content',
        'footer_widgets' => array( 'sidebar-2', 'sidebar-3', 'sidebar-4' ),
        'footer'         => 'page'
    ) );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'slider', 1280, 550, true); //Featured Post Slider Image
	add_image_size( 'featured', 750, 499, true); //Featured Image
	add_image_size( 'small-featured', 360, 240, true); //Small Featured Image

	//@remove Remove check when WordPress 4.8 is released
	if ( function_exists( 'has_custom_logo' ) ) {
		/**
		* Setup Custom Logo Support for theme
		* Supported from WordPress version 4.5 onwards
		* More Info: https://make.wordpress.org/core/2016/03/10/custom-logo/
		*/
		add_theme_support( 'custom-logo',
			array(
		   		'height'		=> 85,
	 			'width'			=> 84,
	 			'flex-height'	=> true,
	 			'flex-width'	=> true,
			)
		);
	}

}
endif; // catchkathmandu_setup
add_action( 'after_setup_theme', 'catchkathmandu_setup' );


/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );

if ( ! function_exists( 'catchkathmandu_background_callback' ) ) :
/**
 * Template for Custom Background
 *
 * To override this in a child theme
 * simply create your own catchkathmandu_background_callback(), and that function will be used instead.
 */
function catchkathmandu_background_callback() {

	/* Get the background image. */
	$image = get_background_image();

	/* If there's an image, just call the normal WordPress callback. We won't do anything here. */
	if ( !empty( $image ) ) {
		_custom_background_cb();
		return;
	}

	/* Get the background color. */
	$color = get_background_color();

	/* If no background color, return. */
	if ( empty( $color ) )
		return;

	/* Use 'background' instead of 'background-color'. */
	$style = "background: #{$color};";

?>
<style type="text/css">body { <?php echo trim( $style ); ?> }</style>
<?php
}
endif; // catchkathmandu_background_callback


if ( ! function_exists( 'catchkathmandu_get_theme_layout' ) ) :
	/**
	 * Returns Theme Layout prioritizing the meta box layouts
	 *
	 * @uses  get_options
	 *
	 * @action wp_head
	 *
	 * @since Catch Kathmandu 4.7
	 */
	function catchkathmandu_get_theme_layout() {
		global $post, $wp_query, $catchkathmandu_options_settings;
   		$options = $catchkathmandu_options_settings;

		// Front page displays in Reading Settings
		$page_on_front = get_option('page_on_front') ;
		$page_for_posts = get_option('page_for_posts');

		// Get Page ID outside Loop
		$page_id = $wp_query->get_queried_object_id();

		// Blog Page setting in Reading Settings
		if ( $page_id == $page_for_posts ) {
			$layout = get_post_meta( $page_for_posts,'catchkathmandu-sidebarlayout', true );
		}
		// Settings for page/post/attachment
		elseif ( $post ) {
	 		if ( is_attachment() ) {
				$parent = $post->post_parent;
				$layout = get_post_meta( $parent,'catchkathmandu-sidebarlayout', true );
			} else {
				$layout = get_post_meta( $post->ID,'catchkathmandu-sidebarlayout', true );
			}
		}

		if ( empty( $layout ) || 'default' == $layout || ( !is_page() && !is_single() ) ) {
			$layout = $options['sidebar_layout'];
		}

		return $layout;
	}
endif; //catchkathmandu_get_theme_layout


/**
 * Add Suport for WooCommerce Plugin
 */
if ( class_exists( 'woocommerce' ) ) {
	add_theme_support( 'woocommerce' );
    require( get_template_directory() . '/inc/catchkathmandu-woocommerce.php' );
}


/**
 * Migrate Logo to New WordPress core Custom Logo
 *
 * Runs if version number saved in theme_mod "logo_version" doesn't match current theme version.
 */
function catchkathmandu_logo_migrate() {
	$ver = get_theme_mod( 'logo_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '3.7' ) >= 0 ) {
		return;
	}

	/**
	 * If a logo has been set previously, update to use logo feature introduced in WordPress 4.5
	 * Then, move custom header image to core header image as hereh, header image is being used as a logo
	 */
	if ( function_exists( 'the_custom_logo' ) ) {

		//Step 1: Move header image to logo
		$header_image = get_header_image();

		if ( ! empty( $header_image ) ) {
			// Since previous logo was stored a URL, convert it to an attachment ID
			$logo = attachment_url_to_postid( $header_image );

			if ( is_int( $logo ) ) {
				set_theme_mod( 'custom_logo', $logo );
			}
		}
		//End Step 1

		//Step 2: Move Custom header image url to core header image
		/**
		 * Get Theme Options Values
		 */
		global $catchkathmandu_options_settings;
	   	$options = $catchkathmandu_options_settings;

		if ( isset( $options['featured_header_image'] ) && '' != $options['featured_header_image'] ) {
			$header_image_id             = attachment_url_to_postid( $options['featured_header_image'] );

			$header_image                = wp_get_attachment_metadata( $header_image_id );

			$header_image_thumbnail_size = wp_get_attachment_image_src( $header_image_id, 'thumbnail' );

			$header_image_data = (object) array(
	            'attachment_id' => $header_image_id,
	            'url'           => $options['featured_header_image'],
	            'thumbnail_url' => isset( $header_image_thumbnail_size[0] ) ? $header_image_thumbnail_size[0] : '',
	            'height'        => $header_image['height'],
	            'width'         => $header_image['width'],
	        );

			set_theme_mod( 'header_image', esc_url_raw( $options['featured_header_image'] ) );

			set_theme_mod( 'header_image_data', $header_image_data );
		}

		//End Step 2

		//Refresh featured header image transient
		delete_transient( 'catchkathmandu_featured_image' );

  		// Update to match logo_version so that script is not executed continously
		set_theme_mod( 'logo_version', '3.7' );
	}
}
add_action( 'after_setup_theme', 'catchkathmandu_logo_migrate' );


/**
 * Migrate Custom CSS to WordPress core Custom CSS
 *
 * Runs if version number saved in theme_mod "custom_css_version" doesn't match current theme version.
 */
function catchkathmandu_custom_css_migrate(){
	$ver = get_theme_mod( 'custom_css_version', false );

	// Return if update has already been run
	if ( version_compare( $ver, '4.7' ) >= 0 ) {
		return;
	}

	if ( function_exists( 'wp_update_custom_css_post' ) ) {
	    // Migrate any existing theme CSS to the core option added in WordPress 4.7.

	    /**
		 * Get Theme Options Values
		 */
		global $catchkathmandu_options_settings;
	   	$options = $catchkathmandu_options_settings;

	    if ( '' != $options['custom_css'] ) {
			$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
			$return   = wp_update_custom_css_post( $core_css . $options['custom_css'] );

	        if ( ! is_wp_error( $return ) ) {
	            // Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
	            unset( $options['custom_css'] );
	            update_option( 'catchkathmandu_options', $options );

	            // Update to match custom_css_version so that script is not executed continously
				set_theme_mod( 'custom_css_version', '4.7' );
	        }
	    }
	}
}
add_action( 'after_setup_theme', 'catchkathmandu_custom_css_migrate' );