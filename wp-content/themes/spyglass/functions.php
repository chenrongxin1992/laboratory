<?php
// add any new or customised functions here
add_action( 'wp_enqueue_scripts', 'spyglass_enqueue_styles' );
function spyglass_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	// Loads our main stylesheet.
	wp_enqueue_style( 'spyglass-child-style', get_stylesheet_uri() );
}

/**
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
function spyglass_theme_setup() {
    load_child_theme_textdomain( 'spyglass', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'spyglass_theme_setup' );