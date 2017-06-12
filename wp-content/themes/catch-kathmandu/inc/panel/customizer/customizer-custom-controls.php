<?php
/**
* @package Catch Themes
* @subpackage Catch_Kathmandu
* @since Catch Kathmandu 3.4
*/


//Custom control for Notes
class Catchkathmandu_Note_Control extends WP_Customize_Control {
	public $type = 'description';

	public function render_content() {
		echo '<h2 class="description">' . $this->label . '</h2>';
	}
}

//Custom control for category multiple select
class Catchkathmandu_Customize_Dropdown_Categories_Control extends WP_Customize_Control {
	public $type = 'dropdown-categories';

	public $name;

	public $descripton;

	public function render_content() {
		$dropdown = wp_dropdown_categories(
			array(
				'name'             => $this->name,
				'echo'             => 0,
				'hide_empty'       => false,
				'show_option_none' => false,
				'hide_if_empty'    => false,
				'show_option_all'  => __( 'All Categories', 'catch-kathmandu' )
			)
		);

		$dropdown = str_replace('<select', '<select multiple = "multiple" style = "height:95px;" ' . $this->get_link(), $dropdown );

		echo '<p class="description">'.  $this->description . '</p>';

		printf(
			'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
			$this->label,
			$dropdown
		);

		echo '<p class="description">'. __( 'Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.', 'catch-kathmandu' ) . '</p>';
	}
}

//Custom control for important link
class Catchkathmandu_Important_Links extends WP_Customize_Control {
    public $type = 'important-links';

    public function render_content() {
    	//Add Theme instruction, Support Forum, Changelog, Donate link, Review, Facebook, Twitter, Google+, Pinterest links
        $important_links = array(
						'theme_instructions' => array(
							'link'	=> esc_url( 'https://catchthemes.com/theme-instructions/catch-kathmandu/' ),
							'text' 	=> __( 'Theme Instructions', 'catch-kathmandu' ),
							),
						'support' => array(
							'link'	=> esc_url( 'https://catchthemes.com/support/' ),
							'text' 	=> __( 'Support', 'catch-kathmandu' ),
							),
						'changelog' => array(
							'link'	=> esc_url( 'https://catchthemes.com/changelogs/catch-kathmandu-theme/' ),
							'text' 	=> __( 'Changelog', 'catch-kathmandu' ),
							),
						'donate' => array(
							'link'	=> esc_url( 'https://catchthemes.com/donate/' ),
							'text' 	=> __( 'Donate Now', 'catch-kathmandu' ),
							),
						'review' => array(
							'link'	=> esc_url( 'https://wordpress.org/support/view/theme-reviews/catch-kathmandu' ),
							'text' 	=> __( 'Review', 'catch-kathmandu' ),
							),
						);
		foreach ( $important_links as $important_link) {
			echo '<p><a target="_blank" href="' . $important_link['link'] .'" >' . esc_attr( $important_link['text'] ) .' </a></p>';
		}
    }
}


/**
  * Custom control for checkbox
  * This class adds a custom-checkbox. The value is stored in the hidden field. This is due to the fact that
  * our theme previously stored 1 and 0 strings as checkbox values
  */
class Catchkathmandu_Customize_Checkbox extends WP_Customize_Control {
	public $type = 'catchkathmandu_custom_checkbox';

	public $name;

	public $descripton;

	public $settings;

	public $default;

	public function render_content() {
		$this->value();
		$this->default;
		?>
		<label>
	        <input type="checkbox" <?php checked( '1', $this->value() ); ?>  /> <?php echo esc_html ( $this->label ); ?>

	        <input type="hidden" <?php $this->link(); ?> value="1" />
        </label>
         <?php if ( !empty( $this->description ) ) : ?>
            <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
        <?php endif; ?>
    <?php }
}