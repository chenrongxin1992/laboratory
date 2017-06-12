<?php
/**
 * Catch Kathmandu Customizer/Theme Options
 *
 * @package Catch Themes
 * @subpackage Catch Kathmandu
 * @since Catch Kathmandu 3.2
 */

/**
 * Implements Catch Kathmandu theme options into Theme Customizer.
 *
 * @param $wp_customize Theme Customizer object
 * @return void
 *
 * @since Catch Kathmandu 3.2
 */
function catchkathmandu_customize_register( $wp_customize ) {
	global $catchkathmandu_options_settings, $catchkathmandu_options_defaults;

    $options = $catchkathmandu_options_settings;

	$defaults = $catchkathmandu_options_defaults;

	//Custom Controls
	require trailingslashit( get_template_directory() ) . 'inc/panel/customizer/customizer-custom-controls.php';

	// Color Scheme added to built in section
	$wp_customize->add_setting( 'catchkathmandu_options[color_scheme]', array(
		'capability' 		=> 'edit_theme_options',
		'default'    		=> $defaults['color_scheme'],
		'sanitize_callback'	=> 'sanitize_key',
		'transport'         => 'refresh',
		'type' 				=> 'option',
	) );

	$wp_customize->add_control( 'catchkathmandu_options[color_scheme]', array(
		'choices'  => catchkathmandu_color_schemes(),
		'label'    => __( 'Color Scheme', 'catch-kathmandu' ),
		'priority' => 5,
		'section'  => 'colors',
		'settings' => 'catchkathmandu_options[color_scheme]',
		'type'     => 'radio',
	) );
	//End Color Scheme

	$theme_slug = 'catchkathmandu_';

	$settings_page_tabs = array(
		'theme_options' => array(
			'id' 			=> 'theme_options',
			'title' 		=> __( 'Theme Options', 'catch-kathmandu' ),
			'description' 	=> __( 'Basic theme Options', 'catch-kathmandu' ),
			'sections' 		=> array(
				'homepage_headline_options' => array(
					'id' 			=> 'homepage_headline_options',
					'title' 		=> __( 'Homepage Headline Options', 'catch-kathmandu' ),
					'description' 	=> '',
				),
				'homepage_featured_content_options' => array(
					'id' 			=> 'homepage_featured_content_options',
					'title' 		=> __( 'Featured Content Options', 'catch-kathmandu' ),
					'description' 	=> '',
				),
				'homepage_settings' => array(
					'id' 			=> 'homepage_settings',
					'title' 		=> __( 'Homepage/Frontpage Settings', 'catch-kathmandu' ),
					'description' 	=> '',
				),
				'responsive_design' => array(
					'id' 			=> 'responsive_design',
					'title' 		=> __( 'Responsive Design', 'catch-kathmandu' ),
					'description' 	=> '',
				),
				'header_options' => array(
					'id' 			=> 'header_options',
					'title' 		=> __( 'Header Options', 'catch-kathmandu' ),
					'description' 	=> '',
				),
				'header_featured_image_options' => array(
					'id' 			=> 'header_featured_image_options',
					'title' 		=> __( 'Header Featured image Options', 'catch-kathmandu' ),
					'description' 	=> '',
				),
				'content_featured_image_options' => array(
					'id' 			=> 'content_featured_image_options',
					'title' 		=> __( 'Content Featured Image Options', 'catch-kathmandu' ),
					'description' 	=> '',
				),
				'layout_options' => array(
					'id' 			=> 'layout_options',
					'title' 		=> __( 'Layout Options', 'catch-kathmandu' ),
					'description' 	=> '',
				),
				'search_text_settings' => array(
					'id' 			=> 'search_text_settings',
					'title' 		=> __( 'Search Text Settings', 'catch-kathmandu' ),
					'description' 	=> '',
				),
				'excerpt_more_tag_settings' => array(
					'id' 			=> 'excerpt_more_tag_settings',
					'title' 		=> __( 'Excerpt / More Tag Settings', 'catch-kathmandu' ),
					'description' 	=> '',
				),
				'feed_redirect' => array(
					'id' 			=> 'feed_redirect',
					'title' 		=> __( 'Feed Redirect', 'catch-kathmandu' ),
					'description' 	=> __( 'This options will be retired in next version as per WordPress.org theme review rules', 'catch-kathmandu' ),
				),
				'custom_css' => array(
					'id' 			=> 'custom_css',
					'title' 		=> __( 'Custom CSS', 'catch-kathmandu' ),
					'description' 	=> '',
				),
				'scrollup_options' => array(
					'id' 			=> 'scrollup_options',
					'title' 		=> __( 'Scroll Up', 'catch-kathmandu' ),
					'description' 	=> '',
				),

			),
		),

		'featured_slider' => array(
			'id' 			=> 'featured_slider',
			'title' 		=> __( 'Featured Slider', 'catch-kathmandu' ),
			'description' 	=> __( 'Featured Slider', 'catch-kathmandu' ),
			'sections' 		=> array(
				'slider_options' => array(
					'id' 			=> 'slider_options',
					'title' 		=> __( 'Slider Options', 'catch-kathmandu' ),
					'description' 	=> '',
				),
			)
		),

		'social_links' => array(
			'id' 			=> 'social_links',
			'title' 		=> __( 'Social Links', 'catch-kathmandu' ),
			'description' 	=> __( 'Add your social links here', 'catch-kathmandu' ),
			'sections' 		=> array(
				'predefined_social_icons' => array(
					'id' 			=> 'predefined_social_icons',
					'title' 		=> __( 'Predefined Social Icons', 'catch-kathmandu' ),
					'description' 	=> '',
				),
			),
		),
		'tools' => array(
			'id' 			=> 'tools',
			'title' 		=> __( 'Tools', 'catch-kathmandu' ),
			'description' 	=> '',
			'sections' 		=> array(
				'header_and_footer_codes' => array(
					'id' 			=> 'header_and_footer_codes',
					'title' 		=> __( 'Header and Footer Codes', 'catch-kathmandu' ),
					'description'	=> ''
				),
			),
		)
	);

	//Add Panels and sections
	foreach ( $settings_page_tabs as $panel ) {
		$wp_customize->add_panel(
			$theme_slug . $panel['id'],
			array(
				'priority' 		=> 200,
				'capability' 	=> 'edit_theme_options',
				'title' 		=> $panel['title'],
				'description' 	=> $panel['description'],
			)
		);

		// Loop through tabs for sections
		foreach ( $panel['sections'] as $section ) {
			$params = array(
								'title'			=> $section['title'],
								'description'	=> $section['description'],
								'panel'			=> $theme_slug . $panel['id']
							);

			if ( isset( $section['active_callback'] ) ) {
				$params['active_callback'] = $section['active_callback'];
			}

			$wp_customize->add_section(
				// $id
				$theme_slug . $section['id'],
				// parameters
				$params

			);
		}
	}

	$settings_parameters = array(
		//Homepage Headline Options
		'homepage_headline' => array(
			'id' 			=> 'homepage_headline',
			'title' 		=> __( 'Homepage Headline Text', 'catch-kathmandu' ),
			'description' 	=> __( 'Appropriate Words: 10', 'catch-kathmandu' ),
			'field_type' 	=> 'textarea',
			'sanitize' 		=> 'wp_kses_post',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_headline_options',
			'default' 		=> $defaults['homepage_headline']
		),
		'homepage_subheadline' => array(
			'id' 			=> 'homepage_subheadline',
			'title' 		=> __( 'Homepage Subheadline Text', 'catch-kathmandu' ),
			'description' 	=> __( 'Appropriate Words: 15', 'catch-kathmandu' ),
			'field_type' 	=> 'textarea',
			'sanitize' 		=> 'wp_kses_post',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_headline_options',
			'default' 		=> $defaults['homepage_subheadline']
		),
		'homepage_headline_button' => array(
			'id' 			=> 'homepage_headline_button',
			'title' 		=> __( 'Homepage Headline Button Text ', 'catch-kathmandu' ),
			'description'	=> __( 'Appropriate Words: 3', 'catch-kathmandu' ),
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_headline_options',
			'default' 		=> $defaults['homepage_headline_button']
		),
		'homepage_headline_url' => array(
			'id' 			=> 'homepage_headline_url',
			'title' 		=> __( 'Homepage Headline Link', 'catch-kathmandu' ),
			'description'	=> __( 'Add link for your homepage headline button', 'catch-kathmandu' ),
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_headline_options',
			'default' 		=> $defaults['homepage_headline_url']
		),
		'disable_homepage_headline' => array(
			'id' 			=> 'disable_homepage_headline',
			'title' 		=> __( 'Check to Disable Homepage Headline', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_headline_options',
			'default' 		=> $defaults['disable_homepage_headline']
		),
		'disable_homepage_subheadline' => array(
			'id' 			=> 'disable_homepage_subheadline',
			'title' 		=> __( 'Check to Disable Homepage Subheadline', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_headline_options',
			'default' 		=> $defaults['disable_homepage_subheadline']
		),
		'disable_homepage_button' => array(
			'id' 			=> 'disable_homepage_button',
			'title' 		=> __( 'Check to Disable Homepage Button', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_headline_options',
			'default' 		=> $defaults['disable_homepage_button']
		),
		//Homepage Featured Content
		'disable_homepage_featured' => array(
			'id' 			=> 'disable_homepage_featured',
			'title' 		=> __( 'Check to Disable Featured Content', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_featured_content_options',
			'default' 		=> $defaults['disable_homepage_featured']
		),
		'homepage_featured_headline' => array(
			'id' 			=> 'homepage_featured_headline',
			'title' 		=> __( 'Headline', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_featured_content_options',
			'default' 		=> $defaults['homepage_featured_headline']
		),
		'homepage_featured_qty' => array(
			'id' 			=> 'homepage_featured_qty',
			'title' 		=> __( 'Number of Featured Content', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'number',
			'sanitize' 		=> 'catchkathmandu_sanitize_number_range',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_featured_content_options',
			'default' 		=> $defaults['homepage_featured_qty'],
			'input_attrs' 	=> array(
					            'style' => 'width: 45px;',
					            'min'   => 0,
					            'max'   => 20,
					            'step'  => 1,
					        	)
		),
		'homepage_featured_layout' => array(
			'id' 			=> 'homepage_featured_layout',
			'title' 		=> __( 'Featured Content Layout', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'radio',
			'sanitize' 		=> 'catchkathmandu_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_featured_content_options',
			'default' 		=> $defaults['homepage_featured_layout'],
			'choices'		=> catchkathmandu_featured_content_layouts(),
		),

		//Homepage/Frontpage Settings
		'enable_posts_home' => array(
			'id' 			=> 'enable_posts_home',
			'title' 		=> __( 'Check to Enable Latest Posts on homepage', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_settings',
			'default' 		=> $defaults['enable_posts_home']
		),
		'front_page_category' => array(
			'id' 			=> 'front_page_category',
			'title' 		=> __( 'Homepage posts categories:', 'catch-kathmandu' ),
			'description'	=> __( 'Only posts that belong to the categories selected here will be displayed on the front page', 'catch-kathmandu' ),
			'field_type' 	=> 'category-multiple',
			'sanitize' 		=> 'catchkathmandu_sanitize_category_list',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_settings',
			'default' 		=> $defaults['front_page_category']
		),
		'move_posts_home' => array(
			'id' 			=> 'move_posts_home',
			'title' 		=> __( 'Check to Move above Featured Content', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'homepage_settings',
			'default' 		=> $defaults['move_posts_home']
		),

		//Responsive Design
		'disable_responsive' => array(
			'id' 			=> 'disable_responsive',
			'title' 		=> __( 'Check to Disable Responsive Design', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'responsive_design',
			'default' 		=> $defaults['disable_responsive']
		),
		'enable_menus' => array(
			'id' 			=> 'enable_menus',
			'title' 		=> __( 'Check to Enable Secondary Menu in Mobile Devices', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'responsive_design',
			'default' 		=> $defaults['enable_menus']
		),

		//Header Options
		'disable_header_right_sidebar' => array(
			'id' 			=> 'disable_header_right_sidebar',
			'title' 		=> __( 'Check to Disable Header Right Sidebar', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_options',
			'default' 		=> $defaults['disable_header_right_sidebar']
		),

		//Content Featured Image Options
		'featured_image' => array(
			'id' 			=> 'featured_image',
			'title' 		=> __( 'Content Featured Image Size', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'select',
			'sanitize' 		=> 'catchkathmandu_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'content_featured_image_options',
			'default' 		=> $defaults['featured_image'],
			'choices'		=> catchkathmandu_content_featured_image_size(),
		),

		//Layout Options
		'sidebar_layout' => array(
			'id' 			=> 'sidebar_layout',
			'title' 		=> __( 'Sidebar Layout Options', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'select',
			'sanitize' 		=> 'catchkathmandu_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'layout_options',
			'default' 		=> $defaults['sidebar_layout'],
			'choices'		=> catchkathmandu_sidebar_layout_options(),
		),
		'content_layout' => array(
			'id' 			=> 'content_layout',
			'title' 		=> __( 'Full Content Display', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'select',
			'sanitize' 		=> 'catchkathmandu_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'layout_options',
			'default' 		=> $defaults['content_layout'],
			'choices'		=> catchkathmandu_content_layout_options(),
		),
		'reset_layout' => array(
			'id' 			=> 'reset_layout',
			'title' 		=> __( 'Check to Reset Layout', 'catch-kathmandu' ),
			'description'	=> __( 'Please refresh the customizer after saving if reset option is used', 'catch-kathmandu' ),
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'layout_options',
			'transport'		=> 'postMessage',
			'default' 		=> $defaults['reset_layout']
		),

		//Search Settings
		'search_display_text' => array(
			'id' 			=> 'search_display_text',
			'title' 		=> __( 'Default Display Text in Search', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'search_text_settings',
			'default' 		=> $defaults['search_display_text']
		),

		//Excerpt More Settings
		'more_tag_text' => array(
			'id' 			=> 'more_tag_text',
			'title' 		=> __( 'More Tag Text', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'excerpt_more_tag_settings',
			'default' 		=> $defaults['more_tag_text']
		),
		'excerpt_length' => array(
			'id' 			=> 'excerpt_length',
			'title' 		=> __( 'Excerpt length(words)', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'number',
			'sanitize' 		=> 'catchkathmandu_sanitize_number_range',
			'panel' 		=> 'theme_options',
			'section' 		=> 'excerpt_more_tag_settings',
			'default' 		=> $defaults['excerpt_length'],
			'input_attrs' 	=> array(
					            'style' => 'width: 45px;',
					            'min'   => 0,
					            'max'   => 999999,
					            'step'  => 1,
					        	)
		),
		'reset_moretag' => array(
			'id' 			=> 'reset_moretag',
			'title' 		=> __( 'Check to Reset Excerpt', 'catch-kathmandu' ),
			'description'	=> __( 'Please refresh the customizer after saving if reset option is used', 'catch-kathmandu' ),
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'excerpt_more_tag_settings',
			'transport'		=> 'postMessage',
			'default' 		=> $defaults['reset_moretag']
		),

		//Feed redirect
		'feed_url' => array(
			'id' 			=> 'feed_url',
			'title' 		=> __( 'Feed Redirect URL', 'catch-kathmandu' ),
			'description'	=> __( 'Add in the Feedburner URL', 'catch-kathmandu' ),
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'theme_options',
			'section' 		=> 'feed_redirect',
			'default' 		=> $defaults['homepage_featured_url']
		),

		//Custom Css
		'custom_css' => array(
			'id' 			=> 'custom_css',
			'title' 		=> __( 'Enter your custom CSS styles', 'catch-kathmandu' ),
			'description' 	=> '',
			'field_type' 	=> 'textarea',
			'sanitize' 		=> 'catchkathmandu_sanitize_custom_css',
			'panel' 		=> 'theme_options',
			'section' 		=> 'custom_css',
			'default' 		=> $defaults['custom_css']
		),

		//Scroll Up
		'disable_scrollup' => array(
			'id' 			=> 'disable_scrollup',
			'title' 		=> __( 'Check to Disable Scroll Up', 'catch-kathmandu' ),
			'description' 	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'scrollup_options',
			'default' 		=> $defaults['disable_scrollup']
		),

		//Slider Options
		'enable_slider' => array(
			'id' 			=> 'enable_slider',
			'title' 		=> __( 'Enable Slider', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'select',
			'sanitize' 		=> 'catchkathmandu_sanitize_select',
			'panel' 		=> 'featured_slider',
			'section' 		=> 'slider_options',
			'default' 		=> $defaults['enable_slider'],
			'choices'		=> catchkathmandu_enable_slider_options(),
		),
		'transition_effect' => array(
			'id' 				=> 'transition_effect',
			'title' 			=> __( 'Transition Effect', 'catch-kathmandu' ),
			'description'		=> '',
			'field_type' 		=> 'select',
			'sanitize' 			=> 'catchkathmandu_sanitize_select',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['transition_effect'],
			'active_callback'	=> 'catchkathmandu_is_slider_active',
			'choices'			=> catchkathmandu_transition_effects(),
		),
		'transition_delay' => array(
			'id' 				=> 'transition_delay',
			'title' 			=> __( 'Transition Delay', 'catch-kathmandu' ),
			'description'		=> '',
			'field_type' 		=> 'number',
			'sanitize' 			=> 'catchkathmandu_sanitize_number_range',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['transition_delay'],
			'active_callback'	=> 'catchkathmandu_is_slider_active',
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 999999999,
						            'step'  => 1,
						        	)
		),
		'transition_duration' => array(
			'id' 				=> 'transition_duration',
			'title' 			=> __( 'Transition Length', 'catch-kathmandu' ),
			'description'		=> '',
			'field_type' 		=> 'number',
			'sanitize' 			=> 'catchkathmandu_sanitize_number_range',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['transition_duration'],
			'active_callback'	=> 'catchkathmandu_is_slider_active',
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 999999999,
						            'step'  => 1,
						        	)
		),
		'select_slider_type' => array(
			'id' 				=> 'select_slider_type',
			'title' 			=> __( 'Select Slider Type', 'catch-kathmandu' ),
			'description'		=> '',
			'field_type' 		=> 'select',
			'sanitize' 			=> 'catchkathmandu_sanitize_select',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['select_slider_type'],
			'active_callback'	=> 'catchkathmandu_is_slider_active',
			'choices'			=> catchkathmandu_slider_types(),
		),
		'slider_qty' => array(
			'id' 				=> 'slider_qty',
			'title' 			=> __( 'Number of Slides', 'catch-kathmandu' ),
			'description'		=> __( 'Customizer page needs to be refreshed after saving if number of slides is changed', 'catch-kathmandu' ),
			'field_type' 		=> 'number',
			'sanitize' 			=> 'catchkathmandu_sanitize_number_range',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['slider_qty'],
			'active_callback'	=> 'catchkathmandu_is_demo_slider_inactive',
			'input_attrs' 		=> array(
						            'style' => 'width: 45px;',
						            'min'   => 0,
						            'max'   => 20,
						            'step'  => 1,
						        	)
		),

		//Featured Post Slider
		'exclude_slider_post' => array(
			'id' 				=> 'exclude_slider_post',
			'title' 			=> __( 'Check to Exclude Slider posts from Homepage posts', 'catch-kathmandu' ),
			'description'		=> __( 'Please refresh the customizer after saving if reset option is used', 'catch-kathmandu' ),
			'field_type' 		=> 'checkbox',
			'sanitize' 			=> 'catchkathmandu_sanitize_checkbox',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['exclude_slider_post'],
			'active_callback' 	=> 'catchkathmandu_is_post_slider_active'
		),

		//Featured Category Slider
		'slider_category' => array(
			'id' 				=> 'slider_category',
			'title' 			=> __( 'Select Slider Categories', 'catch-kathmandu' ),
			'description'		=> __( 'Use this only is you want to display posts from Specific Categories in Featured Slider', 'catch-kathmandu' ),
			'field_type' 		=> 'category-multiple',
			'sanitize' 			=> 'catchkathmandu_sanitize_category_list',
			'panel' 			=> 'featured_slider',
			'section' 			=> 'slider_options',
			'default' 			=> $defaults['slider_category'],
			'active_callback' 	=> 'catchkathmandu_is_category_slider_active'
		),

		//Social Links
		'social_facebook' => array(
			'id' 			=> 'social_facebook',
			'title' 		=> __( 'Facebook', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_facebook']
		),
		'social_twitter' => array(
			'id' 			=> 'social_twitter',
			'title' 		=> __( 'Twitter', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_twitter']
		),
		'social_googleplus' => array(
			'id' 			=> 'social_googleplus',
			'title' 		=> __( 'Google+', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_googleplus']
		),
		'social_pinterest' => array(
			'id' 			=> 'social_pinterest',
			'title' 		=> __( 'Pinterest', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_pinterest']
		),
		'social_youtube' => array(
			'id' 			=> 'social_youtube',
			'title' 		=> __( 'Youtube', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_youtube']
		),
		'social_vimeo' => array(
			'id' 			=> 'social_vimeo',
			'title' 		=> __( 'Vimeo', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_vimeo']
		),
		'social_linkedin' => array(
			'id' 			=> 'social_linkedin',
			'title' 		=> __( 'LinkedIn', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_linkedin']
		),
		'social_slideshare' => array(
			'id' 			=> 'social_slideshare',
			'title' 		=> __( 'Slideshare', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_slideshare']
		),
		'social_foursquare' => array(
			'id' 			=> 'social_foursquare',
			'title' 		=> __( 'Foursquare', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_foursquare']
		),
		'social_flickr' => array(
			'id' 			=> 'social_flickr',
			'title' 		=> __( 'Flickr', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_flickr']
		),
		'social_tumblr' => array(
			'id' 			=> 'social_tumblr',
			'title' 		=> __( 'Tumblr', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_tumblr']
		),
		'social_deviantart' => array(
			'id' 			=> 'social_deviantart',
			'title' 		=> __( 'deviantART', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_deviantart']
		),
		'social_dribbble' => array(
			'id' 			=> 'social_dribbble',
			'title' 		=> __( 'Dribbble', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_dribbble']
		),
		'social_myspace' => array(
			'id' 			=> 'social_myspace',
			'title' 		=> __( 'MySpace', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_myspace']
		),
		'social_wordpress' => array(
			'id' 			=> 'social_wordpress',
			'title' 		=> __( 'WordPress', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_wordpress']
		),
		'social_rss' => array(
			'id' 			=> 'social_rss',
			'title' 		=> __( 'RSS', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_rss']
		),
		'social_delicious' => array(
			'id' 			=> 'social_delicious',
			'title' 		=> __( 'Delicious', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_delicious']
		),
		'social_lastfm' => array(
			'id' 			=> 'social_lastfm',
			'title' 		=> __( 'Last.fm', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_lastfm']
		),
		'social_instagram' => array(
			'id' 			=> 'social_instagram',
			'title' 		=> __( 'Instagram', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_instagram']
		),
		'social_github' => array(
			'id' 			=> 'social_github',
			'title' 		=> __( 'GitHub', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_github']
		),
		'social_vkontakte' => array(
			'id' 			=> 'social_vkontakte',
			'title' 		=> __( 'Vkontakte', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_vkontakte']
		),
		'social_myworld' => array(
			'id' 			=> 'social_myworld',
			'title' 		=> __( 'My World', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_myworld']
		),
		'social_odnoklassniki' => array(
			'id' 			=> 'social_odnoklassniki',
			'title' 		=> __( 'Odnoklassniki', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_odnoklassniki']
		),
		'social_goodreads' => array(
			'id' 			=> 'social_goodreads',
			'title' 		=> __( 'Goodreads', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_goodreads']
		),
		'social_skype' => array(
			'id' 			=> 'social_skype',
			'title' 		=> __( 'Skype', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_skype']
		),
		'social_soundcloud' => array(
			'id' 			=> 'social_soundcloud',
			'title' 		=> __( 'Soundcloud', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_soundcloud']
		),
		'social_email' => array(
			'id' 			=> 'social_email',
			'title' 		=> __( 'Email', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'sanitize_email',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_email']
		),
		'social_contact' => array(
			'id' 			=> 'social_contact',
			'title' 		=> __( 'Contact', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_contact']
		),
		'social_xing' => array(
			'id' 			=> 'social_xing',
			'title' 		=> __( 'Xing', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_xing']
		),
		'social_meetup' => array(
			'id' 			=> 'social_meetup',
			'title' 		=> __( 'Meetup', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'social_links',
			'section' 		=> 'predefined_social_icons',
			'default' 		=> $defaults['social_meetup']
		),

		'analytic_header' => array(
			'id' 			=> 'analytic_header',
			'title' 		=> __( 'Header Code', 'catch-kathmandu' ),
			'description'	=> __( 'Here you can put scripts from Google, Facebook, Twitter, Add This etc. which will load on Header', 'catch-kathmandu' ),
			'field_type' 	=> 'textarea',
			'sanitize' 		=> 'wp_kses_stripslashes',
			'panel' 		=> 'tools',
			'section' 		=> 'header_and_footer_codes',
			'default' 		=> $defaults['analytic_header']
		),

		'analytic_footer' => array(
			'id' 			=> 'analytic_footer',
			'title' 		=> __( 'Footer Code', 'catch-kathmandu' ),
			'description'	=> __( 'Here you can put scripts from Google, Facebook, Twitter, Add This etc. which will load on footer', 'catch-kathmandu' ),
			'field_type' 	=> 'textarea',
			'sanitize' 		=> 'wp_kses_stripslashes( $string );',
			'panel' 		=> 'tools',
			'section' 		=> 'header_and_footer_codes',
			'default' 		=> $defaults['analytic_footer']
		),

	);

	if( !function_exists( 'has_custom_logo' ) ) {
		$settings_header_image = array(
			//Header Featured image Options
		'enable_featured_header_image' => array(
			'id' 			=> 'enable_featured_header_image',
			'title' 		=> __( 'Enable Featured Header Image', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'select',
			'sanitize' 		=> 'catchkathmandu_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_featured_image_options',
			'default' 		=> $defaults['enable_featured_header_image'],
			'choices'		=> catchkathmandu_enable_header_featured_image(),
		),
		'page_featured_image' => array(
			'id' 			=> 'page_featured_image',
			'title' 		=> __( 'Page/Post Featured Image Size', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'select',
			'sanitize' 		=> 'catchkathmandu_sanitize_select',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_featured_image_options',
			'default' 		=> $defaults['page_featured_image'],
			'choices'		=> catchkathmandu_page_post_featured_image_size(),
		),
		'featured_header_image' => array(
			'id' 			=> 'featured_header_image',
			'title' 		=> __( 'Featured Header Image', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'image',
			'sanitize' 		=> 'catchkathmandu_sanitize_image',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_featured_image_options',
			'default' 		=> $defaults['featured_header_image']
		),
		'featured_header_image_alt' => array(
			'id' 			=> 'featured_header_image_alt',
			'title' 		=> __( 'Featured Header Image Alt/Title Tag', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'text',
			'sanitize' 		=> 'sanitize_text_field',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_featured_image_options',
			'default' 		=> $defaults['featured_header_image_alt']
		),
		'featured_header_image_url' => array(
			'id' 			=> 'featured_header_image_url',
			'title' 		=> __( 'Featured Header Image Link URL', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'url',
			'sanitize' 		=> 'esc_url_raw',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_featured_image_options',
			'default' 		=> $defaults['featured_header_image_url']
		),
		'featured_header_image_base' => array(
			'id' 			=> 'featured_header_image_base',
			'title' 		=> __( 'Check to Open Link in New Tab/Window', 'catch-kathmandu' ),
			'description'	=> '',
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_featured_image_options',
			'default' 		=> $defaults['featured_header_image_base']
		),
		'reset_featured_image' => array(
			'id' 			=> 'reset_featured_image',
			'title' 		=> __( 'Check to Reset Header Featured Image Options', 'catch-kathmandu' ),
			'description'	=> __( 'Please refresh the customizer after saving if reset option is used', 'catch-kathmandu' ),
			'field_type' 	=> 'checkbox',
			'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
			'panel' 		=> 'theme_options',
			'section' 		=> 'header_featured_image_options',
			'transport'		=> 'postMessage',
			'default' 		=> $defaults['reset_featured_image']
		),
		);
		$settings_parameters = array_merge( $settings_parameters, $settings_header_image);
	}
	else {
		$settings_header_image = array(
			//Header Featured image Options
			'enable_featured_header_image' => array(
				'id' 			=> 'enable_featured_header_image',
				'title' 		=> __( 'Enable Featured Header Image', 'catch-kathmandu' ),
				'description'	=> '',
				'field_type' 	=> 'select',
				'sanitize' 		=> 'catchkathmandu_sanitize_select',
				'panel' 		=> 'theme_options',
				'priority'		=> 1,
				'section' 		=> 'header_image',
				'default' 		=> $defaults['enable_featured_header_image'],
				'choices'		=> catchkathmandu_enable_header_featured_image(),
			),
			'page_featured_image' => array(
				'id' 			=> 'page_featured_image',
				'title' 		=> __( 'Page/Post Featured Image Size', 'catch-kathmandu' ),
				'description'	=> '',
				'field_type' 	=> 'select',
				'sanitize' 		=> 'catchkathmandu_sanitize_select',
				'panel' 		=> 'theme_options',
				'section' 		=> 'header_image',
				'default' 		=> $defaults['page_featured_image'],
				'choices'		=> catchkathmandu_page_post_featured_image_size(),
			),
			'featured_header_image_alt' => array(
				'id' 			=> 'featured_header_image_alt',
				'title' 		=> __( 'Featured Header Image Alt/Title Tag', 'catch-kathmandu' ),
				'description'	=> '',
				'field_type' 	=> 'text',
				'sanitize' 		=> 'sanitize_text_field',
				'panel' 		=> 'theme_options',
				'section' 		=> 'header_image',
				'default' 		=> $defaults['featured_header_image_alt']
			),
			'featured_header_image_url' => array(
				'id' 			=> 'featured_header_image_url',
				'title' 		=> __( 'Featured Header Image Link URL', 'catch-kathmandu' ),
				'description'	=> '',
				'field_type' 	=> 'url',
				'sanitize' 		=> 'esc_url_raw',
				'panel' 		=> 'theme_options',
				'section' 		=> 'header_image',
				'default' 		=> $defaults['featured_header_image_url']
			),
			'featured_header_image_base' => array(
				'id' 			=> 'featured_header_image_base',
				'title' 		=> __( 'Check to Open Link in New Tab/Window', 'catch-kathmandu' ),
				'description'	=> '',
				'field_type' 	=> 'checkbox',
				'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
				'panel' 		=> 'theme_options',
				'section' 		=> 'header_image',
				'default' 		=> $defaults['featured_header_image_base']
			),
			'reset_featured_image' => array(
				'id' 			=> 'reset_featured_image',
				'title' 		=> __( 'Check to Reset Header Featured Image Options', 'catch-kathmandu' ),
				'description'	=> __( 'Please refresh the customizer after saving if reset option is used', 'catch-kathmandu' ),
				'field_type' 	=> 'checkbox',
				'sanitize' 		=> 'catchkathmandu_sanitize_checkbox',
				'panel' 		=> 'theme_options',
				'section' 		=> 'header_image',
				'transport'		=> 'postMessage',
				'default' 		=> $defaults['reset_featured_image']
			),
		);
		$settings_parameters = array_merge( $settings_parameters, $settings_header_image);
	}

	//@remove Remove if block and custom_css from $settings_paramater when WordPress 5.0 is released
	if( function_exists( 'wp_update_custom_css_post' ) ) {
		unset( $settings_parameters['custom_css'] );
	}

	foreach ( $settings_parameters as $option ) {
		$section = $option['section'];

		if( 'header_image' != $option['section'] ) {
			$section = $theme_slug . $option['section'];
		}

		if( 'image' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default']
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,$theme_slug . 'options[' . $option['id'] . ']',
					array(
						'label'		=> $option['title'],
						'section'   => $section,
						'settings'  => $theme_slug . 'options[' . $option['id'] . ']',
					)
				)
			);
		}
		else if ('checkbox' == $option['field_type'] ) {
			$transport = isset( $option['transport'] ) ? $option['transport'] : 'refresh';
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'              => 'option',
					'sanitize_callback' => $option['sanitize'],
					'default'           => $option['default'],
					'transport'         => $transport
				)
			);

			$params = array(
						'label'		=> $option['title'],
						'section'   => $section,
						'settings'  => $theme_slug . 'options[' . $option['id'] . ']',
						'name'  	=> $theme_slug . 'options[' . $option['id'] . ']',
					);

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			$wp_customize->add_control(
				new Catchkathmandu_Customize_Checkbox(
					$wp_customize,$theme_slug . 'options[' . $option['id'] . ']',
					$params
				)
			);
		}
		else if ('category-multiple' == $option['field_type'] ) {
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize'],
					'default'			=> $option['default']
				)
			);

			$params = array(
						'label'			=> $option['title'],
						'section'		=> $section,
						'settings'		=> $theme_slug . 'options[' . $option['id'] . ']',
						'description'	=> $option['description'],
						'name'	 		=> $theme_slug . 'options[' . $option['id'] . ']',
					);

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			$wp_customize->add_control(
				new Catchkathmandu_Customize_Dropdown_Categories_Control (
					$wp_customize,
					$theme_slug . 'options[' . $option['id'] . ']',
					$params
				)
			);
		}
		else {
			//Normal Loop
			$wp_customize->add_setting(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				// parameters array
				array(
					'default'			=> $option['default'],
					'type'				=> 'option',
					'sanitize_callback'	=> $option['sanitize']
				)
			);

			// Add setting control
			$params = array(
					'label'       => $option['title'],
					'section'     => $section,
					'settings'    => $theme_slug . 'options[' . $option['id'] . ']',
					'type'        => $option['field_type'],
					'description' => $option['description'],
				) ;

			if ( isset( $option['choices']  ) ){
				$params['choices'] = $option['choices'];
			}

			if ( isset( $option['priority']  ) ){
				$params['priority'] = $option['priority'];
			}

			if ( isset( $option['active_callback']  ) ){
				$params['active_callback'] = $option['active_callback'];
			}

			if ( isset( $option['input_attrs']  ) ){
				$params['input_attrs'] = $option['input_attrs'];
			}

			$wp_customize->add_control(
				// $id
				$theme_slug . 'options[' . $option['id'] . ']',
				$params
			);
		}
	}

	//Add featured content elements with respect to no of featured content
	for ( $i = 1; $i <= $options['homepage_featured_qty']; $i++ ) {
		$wp_customize->add_setting(
			// $id
			$theme_slug . 'options[homepage_featured_content_note][' . $i . ']',
			// parameters array
			array(
				'type'				=> 'option',
				'sanitize_callback'	=> 'sanitize_text_field'
			)
		);

		$wp_customize->add_control(
			new Catchkathmandu_Note_Control(
				$wp_customize, $theme_slug . 'options[homepage_featured_content_note][' . $i . ']',
				array(
					'label'		=> sprintf( __( 'Featured Content #%s', 'catch-kathmandu' ), $i ),
					'section'   => $theme_slug .'homepage_featured_content_options',
					'settings'  => $theme_slug . 'options[homepage_featured_content_note][' . $i . ']',
				)
			)
		);

		$wp_customize->add_setting(
			// $id
			$theme_slug . 'options[homepage_featured_image][' . $i . ']',
			// parameters array
			array(
				'type'				=> 'option',
				'sanitize_callback'	=> 'catchkathmandu_sanitize_image'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize, $theme_slug . 'options[homepage_featured_image][' . $i . ']',
				array(
					'label'		=> __( 'Image', 'catch-kathmandu' ),
					'section'   => $theme_slug .'homepage_featured_content_options',
					'settings'  => $theme_slug . 'options[homepage_featured_image][' . $i . ']',
				)
			)
		);

		$wp_customize->add_setting(
			// $id
			$theme_slug . 'options[homepage_featured_url][' . $i . ']',
			// parameters array
			array(
				'type'				=> 'option',
				'sanitize_callback'	=> 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			$theme_slug . 'options[homepage_featured_url][' . $i . ']',
			array(
				'label'		=> __( 'Link URL', 'catch-kathmandu' ),
				'section'	=> $theme_slug .'homepage_featured_content_options',
				'settings'	=> $theme_slug . 'options[homepage_featured_url][' . $i . ']',
				'type'		=> 'url'
			)
		);

		$wp_customize->add_setting(
			// $id
			$theme_slug . 'options[homepage_featured_base][' . $i . ']',
			// parameters array
			array(
				'type'				=> 'option',
				'sanitize_callback'	=> 'catchkathmandu_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			$theme_slug . 'options[homepage_featured_base][' . $i . ']',
			array(
				'label'		=> __( 'Target. Open Link in New Window?', 'catch-kathmandu' ),
				'section'	=> $theme_slug .'homepage_featured_content_options',
				'settings'	=> $theme_slug . 'options[homepage_featured_base][' . $i . ']',
				'type'		=> 'text'
			)
		);

		$wp_customize->add_setting(
			// $id
			$theme_slug . 'options[homepage_featured_title][' . $i . ']',
			// parameters array
			array(
				'type'				=> 'option',
				'sanitize_callback'	=> 'sanitize_text_field'
			)
		);

		$wp_customize->add_control(
			$theme_slug . 'options[homepage_featured_title][' . $i . ']',
			array(
				'label'			=> __( 'Title', 'catch-kathmandu' ),
				'section'		=> $theme_slug .'homepage_featured_content_options',
				'settings'		=> $theme_slug . 'options[homepage_featured_title][' . $i . ']',
				'description'	=> __( 'Leave empty if you want to remove title', 'catch-kathmandu' ),
				'type'			=> 'text'
			)
		);

		$wp_customize->add_setting(
			// $id
			$theme_slug . 'options[homepage_featured_content][' . $i . ']',
			// parameters array
			array(
				'type'				=> 'option',
				'sanitize_callback'	=> 'wp_kses_post'
			)
		);

		$wp_customize->add_control(
			$theme_slug . 'options[homepage_featured_content][' . $i . ']',
			array(
				'label'			=> __( 'Content', 'catch-kathmandu' ),
				'section'		=> $theme_slug .'homepage_featured_content_options',
				'settings'		=> $theme_slug . 'options[homepage_featured_content][' . $i . ']',
				'description'	=> __( 'Appropriate Words: 10', 'catch-kathmandu' ),
				'type'			=> 'textarea'
			)
		);
	}

	//Add featured post elements with respect to no of featured sliders
	for ( $i = 1; $i <= $options['slider_qty']; $i++ ) {
		$wp_customize->add_setting(
			// $id
			$theme_slug . 'options[featured_slider][' . $i . ']',
			// parameters array
			array(
				'type'				=> 'option',
				'sanitize_callback'	=> 'catchkathmandu_sanitize_post_id'
			)
		);

		$wp_customize->add_control(
			$theme_slug . 'options[featured_slider][' . $i . ']',
			array(
				'label'		=> sprintf( __( 'Featured Post Slider #%s', 'catch-kathmandu' ), $i ),
				'section'   => $theme_slug .'slider_options',
				'settings'  => $theme_slug . 'options[featured_slider][' . $i . ']',
				'type'		=> 'text',
					'input_attrs' => array(
	        		'style' => 'width: 100px;'
	    		),
				'active_callback' 	=> 'catchkathmandu_is_post_slider_active'
			)
		);
	}


	// Reset all settings to default
	$wp_customize->add_section( 'catchkathmandu_reset_all_settings', array(
		'description'	=> __( 'Caution: Reset all settings to default. Refresh the page after save to view full effects.', 'catch-kathmandu' ),
		'priority' 		=> 700,
		'title'    		=> __( 'Reset all settings', 'catch-kathmandu' ),
	) );

	$wp_customize->add_setting( 'catchkathmandu_options[reset_all_settings]', array(
		'capability'		=> 'edit_theme_options',
		'sanitize_callback' => 'catchkathmandu_sanitize_checkbox',
		'type'				=> 'option',
		'transport'			=> 'postMessage',
	) );

	$wp_customize->add_control( 'catchkathmandu_options[reset_all_settings]', array(
		'label'    => __( 'Check to reset all settings to default', 'catch-kathmandu' ),
		'section'  => 'catchkathmandu_reset_all_settings',
		'settings' => 'catchkathmandu_options[reset_all_settings]',
		'type'     => 'checkbox'
	) );
	// Reset all settings to default end

	//Important Links
	$wp_customize->add_section( 'important_links', array(
		'priority' 		=> 999,
		'title'   	 	=> __( 'Important Links', 'catch-kathmandu' ),
	) );

	/**
	 * Has dummy Sanitizaition function as it contains no value to be sanitized
	 */
	$wp_customize->add_setting( 'important_links', array(
		'sanitize_callback'	=> 'sanitize_text_field',
	) );

	$wp_customize->add_control( new Catchkathmandu_Important_Links( $wp_customize, 'important_links', array(
        'label'   	=> __( 'Important Links', 'catch-kathmandu' ),
        'section'  	=> 'important_links',
        'settings' 	=> 'important_links',
        'type'     	=> 'important_links',
    ) ) );
    //Important Links End
}
add_action( 'customize_register', 'catchkathmandu_customize_register' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously for catchkathmandu.
 * And flushes out all transient data on preview
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_customize_preview() {
	//Remove transients on preview
	catchkathmandu_themeoption_invalidate_caches();

	global $catchkathmandu_options_defaults ,$catchkathmandu_options_settings;

	$catchkathmandu_options_settings = catchkathmandu_options_set_defaults( $catchkathmandu_options_defaults );
}
add_action( 'customize_preview_init', 'catchkathmandu_customize_preview' );
add_action( 'customize_save', 'catchkathmandu_customize_preview' );


/**
 * Custom scripts and styles on Customizer for Catch Kathmandu
 *
 * @since Catch Kathmandu 3.4
 */
function catchkathmandu_customize_scripts() {
	wp_enqueue_script( 'catchkathmandu_customizer_custom', get_template_directory_uri() . '/inc/panel/js/customizer-custom-scripts.js', array( 'jquery' ), '20140108', true );

    $catchkathmandu_data = array(
		'reset_message' => esc_html__( 'Refresh the customizer page after saving to view reset effects', 'catch-kathmandu' ),
		'reset_options' => array(
			'catchkathmandu_options[reset_featured_image]',
			'catchkathmandu_options[reset_layout]',
			'catchkathmandu_options[reset_moretag]',
			'catchkathmandu_options[reset_all_settings]',
		)
	);

	// Send reset message as object to custom customizer js
	wp_localize_script( 'catchkathmandu_customizer_custom', 'catchkathmandu_data', $catchkathmandu_data );
}
add_action( 'customize_controls_enqueue_scripts', 'catchkathmandu_customize_scripts' );


//Active callbacks for customizer
require trailingslashit( get_template_directory() ) . 'inc/panel/customizer/customizer-active-callbacks.php';

//Sanitize functions for customizer
require trailingslashit( get_template_directory() ) . 'inc/panel/customizer/customizer-sanitize-functions.php';

//Sanitize functions for customizer
require trailingslashit( get_template_directory() ) . 'inc/panel/customizer/upgrade-button/class-customize.php';