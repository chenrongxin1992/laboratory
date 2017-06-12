<?php
/**
 * @package Catch Themes
 * @subpackage Catch_Kathmandu
 * @since Catch Kathmandu 3.4
 */


if( ! function_exists( 'catchkathmandu_is_slider_active' ) ) :
	/**
	* Return true if slider is active
	*
	* @since  Catch Kathmandu 3.4
	*/
	function catchkathmandu_is_slider_active( $control ) {
		global $wp_query;

		$page_id = $wp_query->get_queried_object_id();

		// Front page display in Reading Settings
		$page_for_posts = get_option('page_for_posts');

		$enable = $control->manager->get_setting( 'catchkathmandu_options[enable_slider]' )->value();

		//return true only if previwed page on customizer matches the type of slider option selected
		return ( 'enable-slider-allpage' == $enable  || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && 'enable-slider-homepage' == $enable  ) );
	}
endif;


if( ! function_exists( 'catchkathmandu_is_demo_slider_inactive' ) ) :
	/**
	* Return true if demo slider is inactive
	*
	* @since  Catch Kathmandu 3.4
	*/
	function catchkathmandu_is_demo_slider_inactive( $control ) {
		global $wp_query;

		$page_id = $wp_query->get_queried_object_id();

		// Front page display in Reading Settings
		$page_for_posts = get_option('page_for_posts');

		$enable = $control->manager->get_setting( 'catchkathmandu_options[enable_slider]' )->value();

		$type 	= $control->manager->get_setting( 'catchkathmandu_options[select_slider_type]' )->value();

		//return true only if previwed page on customizer matches the type of slider option selected
		return ( ( 'enable-slider-allpage' == $enable  || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && 'enable-slider-homepage' == $enable  ) ) && !( 'demo-slider' == $type ) );
	}
endif;


if( ! function_exists( 'catchkathmandu_is_post_slider_active' ) ) :
	/**
	* Return true if post slider is active
	*
	* @since  Catch Kathmandu 3.4
	*/
	function catchkathmandu_is_post_slider_active( $control ) {
		global $wp_query;

		$page_id = $wp_query->get_queried_object_id();

		// Front page display in Reading Settings
		$page_for_posts = get_option('page_for_posts');

		$enable = $control->manager->get_setting( 'catchkathmandu_options[enable_slider]' )->value();

		$type 	= $control->manager->get_setting( 'catchkathmandu_options[select_slider_type]' )->value();

		//return true only if previwed page on customizer matches the type of slider option selected
		return ( ( 'enable-slider-allpage' == $enable  || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && 'enable-slider-homepage' == $enable  ) ) && 'post-slider' == $type );
	}
endif;


if( ! function_exists( 'catchkathmandu_is_category_slider_active' ) ) :
	/**
	* Return true if category slider is active
	*
	* @since  Catch Kathmandu 3.4
	*/
	function catchkathmandu_is_category_slider_active( $control ) {
		global $wp_query;

		$page_id = $wp_query->get_queried_object_id();

		// Front page display in Reading Settings
		$page_for_posts = get_option('page_for_posts');

		$enable = $control->manager->get_setting( 'catchkathmandu_options[enable_slider]' )->value();

		$type 	= $control->manager->get_setting( 'catchkathmandu_options[select_slider_type]' )->value();

		//return true only if previwed page on customizer matches the type of slider option selected
		return ( ( 'enable-slider-allpage' == $enable  || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) && 'enable-slider-homepage' == $enable  ) ) && 'category-slider' == $type );
	}
endif;