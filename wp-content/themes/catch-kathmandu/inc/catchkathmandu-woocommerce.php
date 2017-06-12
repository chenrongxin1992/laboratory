<?php
/**
 * Adding support for WooCommerce Plugin
 * 
 * uses remove_action to remove the WooCommerce Wrapper and add_action to add Catch Kathmandu Wrapper
 *
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

if ( ! function_exists( 'catchkathmandu_woocommerce_start' ) ) :
function catchkathmandu_woocommerce_start() {
	echo '<div id="primary" class="content-area"><div id="content" class="site-content woocommerce" role="main">';
}
endif; //catchkathmandu_woocommerce_start
add_action('woocommerce_before_main_content', 'catchkathmandu_woocommerce_start', 15);

if ( ! function_exists( 'catchkathmandu_woocommerce_end' ) ) :
function catchkathmandu_woocommerce_end() {
	echo '</div></div>';
}
endif; //catchkathmandu_woocommerce_end
add_action('woocommerce_after_main_content', 'catchkathmandu_woocommerce_end', 15);