<?php
/**
 * Internationalization helper.
 *
 * @package     Kirki
 * @category    Core
 * @author      Aristeides Stathopoulos
 * @copyright   Copyright (c) 2016, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       1.0
 */

if ( ! class_exists( 'Kirki_l10n' ) ) {

	/**
	 * Handles translations
	 */
	class Kirki_l10n {

		/**
		 * The plugin textdomain
		 *
		 * @access protected
		 * @var string
		 */
		protected $textdomain = 'seos-magazine';

		/**
		 * The class constructor.
		 * Adds actions & filters to handle the rest of the methods.
		 *
		 * @access public
		 */
		public function __construct() {

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		}

		/**
		 * Load the plugin textdomain
		 *
		 * @access public
		 */
		public function load_textdomain() {

			if ( null !== $this->get_path() ) {
				load_textdomain( $this->textdomain, $this->get_path() );
			}
			load_plugin_textdomain( $this->textdomain, false, Kirki::$path . '/languages' );

		}

		/**
		 * Gets the path to a translation file.
		 *
		 * @access protected
		 * @return string Absolute path to the translation file.
		 */
		protected function get_path() {
			$path_found = false;
			$found_path = null;
			foreach ( $this->get_paths() as $path ) {
				if ( $path_found ) {
					continue;
				}
				$path = wp_normalize_path( $path );
				if ( file_exists( $path ) ) {
					$path_found = true;
					$found_path = $path;
				}
			}

			return $found_path;

		}

		/**
		 * Returns an array of paths where translation files may be located.
		 *
		 * @access protected
		 * @return array
		 */
		protected function get_paths() {

			return array(
				WP_LANG_DIR . '/' . $this->textdomain . '-' . get_locale() . '.mo',
				Kirki::$path . '/languages/' . $this->textdomain . '-' . get_locale() . '.mo',
			);

		}

		/**
		 * Shortcut method to get the translation strings
		 *
		 * @static
		 * @access public
		 * @param string $config_id The config ID. See Kirki_Config.
		 * @return array
		 */
		public static function get_strings( $config_id = 'global' ) {

			$translation_strings = array(
				'background-color'      => esc_attr__( 'Background Color', 'seos-magazine' ),
				'background-image'      => esc_attr__( 'Background Image', 'seos-magazine' ),
				'no-repeat'             => esc_attr__( 'No Repeat', 'seos-magazine' ),
				'repeat-all'            => esc_attr__( 'Repeat All', 'seos-magazine' ),
				'repeat-x'              => esc_attr__( 'Repeat Horizontally', 'seos-magazine' ),
				'repeat-y'              => esc_attr__( 'Repeat Vertically', 'seos-magazine' ),
				'inherit'               => esc_attr__( 'Inherit', 'seos-magazine' ),
				'background-repeat'     => esc_attr__( 'Background Repeat', 'seos-magazine' ),
				'cover'                 => esc_attr__( 'Cover', 'seos-magazine' ),
				'contain'               => esc_attr__( 'Contain', 'seos-magazine' ),
				'background-size'       => esc_attr__( 'Background Size', 'seos-magazine' ),
				'fixed'                 => esc_attr__( 'Fixed', 'seos-magazine' ),
				'scroll'                => esc_attr__( 'Scroll', 'seos-magazine' ),
				'background-attachment' => esc_attr__( 'Background Attachment', 'seos-magazine' ),
				'left-top'              => esc_attr__( 'Left Top', 'seos-magazine' ),
				'left-center'           => esc_attr__( 'Left Center', 'seos-magazine' ),
				'left-bottom'           => esc_attr__( 'Left Bottom', 'seos-magazine' ),
				'right-top'             => esc_attr__( 'Right Top', 'seos-magazine' ),
				'right-center'          => esc_attr__( 'Right Center', 'seos-magazine' ),
				'right-bottom'          => esc_attr__( 'Right Bottom', 'seos-magazine' ),
				'center-top'            => esc_attr__( 'Center Top', 'seos-magazine' ),
				'center-center'         => esc_attr__( 'Center Center', 'seos-magazine' ),
				'center-bottom'         => esc_attr__( 'Center Bottom', 'seos-magazine' ),
				'background-position'   => esc_attr__( 'Background Position', 'seos-magazine' ),
				'background-opacity'    => esc_attr__( 'Background Opacity', 'seos-magazine' ),
				'on'                    => esc_attr__( 'ON', 'seos-magazine' ),
				'off'                   => esc_attr__( 'OFF', 'seos-magazine' ),
				'all'                   => esc_attr__( 'All', 'seos-magazine' ),
				'cyrillic'              => esc_attr__( 'Cyrillic', 'seos-magazine' ),
				'cyrillic-ext'          => esc_attr__( 'Cyrillic Extended', 'seos-magazine' ),
				'devanagari'            => esc_attr__( 'Devanagari', 'seos-magazine' ),
				'greek'                 => esc_attr__( 'Greek', 'seos-magazine' ),
				'greek-ext'             => esc_attr__( 'Greek Extended', 'seos-magazine' ),
				'khmer'                 => esc_attr__( 'Khmer', 'seos-magazine' ),
				'latin'                 => esc_attr__( 'Latin', 'seos-magazine' ),
				'latin-ext'             => esc_attr__( 'Latin Extended', 'seos-magazine' ),
				'vietnamese'            => esc_attr__( 'Vietnamese', 'seos-magazine' ),
				'hebrew'                => esc_attr__( 'Hebrew', 'seos-magazine' ),
				'arabic'                => esc_attr__( 'Arabic', 'seos-magazine' ),
				'bengali'               => esc_attr__( 'Bengali', 'seos-magazine' ),
				'gujarati'              => esc_attr__( 'Gujarati', 'seos-magazine' ),
				'tamil'                 => esc_attr__( 'Tamil', 'seos-magazine' ),
				'telugu'                => esc_attr__( 'Telugu', 'seos-magazine' ),
				'thai'                  => esc_attr__( 'Thai', 'seos-magazine' ),
				'serif'                 => _x( 'Serif', 'font style', 'seos-magazine' ),
				'sans-serif'            => _x( 'Sans Serif', 'font style', 'seos-magazine' ),
				'monospace'             => _x( 'Monospace', 'font style', 'seos-magazine' ),
				'font-family'           => esc_attr__( 'Font Family', 'seos-magazine' ),
				'font-size'             => esc_attr__( 'Font Size', 'seos-magazine' ),
				'font-weight'           => esc_attr__( 'Font Weight', 'seos-magazine' ),
				'line-height'           => esc_attr__( 'Line Height', 'seos-magazine' ),
				'font-style'            => esc_attr__( 'Font Style', 'seos-magazine' ),
				'letter-spacing'        => esc_attr__( 'Letter Spacing', 'seos-magazine' ),
				'top'                   => esc_attr__( 'Top', 'seos-magazine' ),
				'bottom'                => esc_attr__( 'Bottom', 'seos-magazine' ),
				'left'                  => esc_attr__( 'Left', 'seos-magazine' ),
				'right'                 => esc_attr__( 'Right', 'seos-magazine' ),
				'center'                => esc_attr__( 'Center', 'seos-magazine' ),
				'justify'               => esc_attr__( 'Justify', 'seos-magazine' ),
				'color'                 => esc_attr__( 'Color', 'seos-magazine' ),
				'add-image'             => esc_attr__( 'Add Image', 'seos-magazine' ),
				'change-image'          => esc_attr__( 'Change Image', 'seos-magazine' ),
				'no-image-selected'     => esc_attr__( 'No Image Selected', 'seos-magazine' ),
				'add-file'              => esc_attr__( 'Add File', 'seos-magazine' ),
				'change-file'           => esc_attr__( 'Change File', 'seos-magazine' ),
				'no-file-selected'      => esc_attr__( 'No File Selected', 'seos-magazine' ),
				'remove'                => esc_attr__( 'Remove', 'seos-magazine' ),
				'select-font-family'    => esc_attr__( 'Select a font-family', 'seos-magazine' ),
				'variant'               => esc_attr__( 'Variant', 'seos-magazine' ),
				'subsets'               => esc_attr__( 'Subset', 'seos-magazine' ),
				'size'                  => esc_attr__( 'Size', 'seos-magazine' ),
				'height'                => esc_attr__( 'Height', 'seos-magazine' ),
				'spacing'               => esc_attr__( 'Spacing', 'seos-magazine' ),
				'ultra-light'           => esc_attr__( 'Ultra-Light 100', 'seos-magazine' ),
				'ultra-light-italic'    => esc_attr__( 'Ultra-Light 100 Italic', 'seos-magazine' ),
				'light'                 => esc_attr__( 'Light 200', 'seos-magazine' ),
				'light-italic'          => esc_attr__( 'Light 200 Italic', 'seos-magazine' ),
				'book'                  => esc_attr__( 'Book 300', 'seos-magazine' ),
				'book-italic'           => esc_attr__( 'Book 300 Italic', 'seos-magazine' ),
				'regular'               => esc_attr__( 'Normal 400', 'seos-magazine' ),
				'italic'                => esc_attr__( 'Normal 400 Italic', 'seos-magazine' ),
				'medium'                => esc_attr__( 'Medium 500', 'seos-magazine' ),
				'medium-italic'         => esc_attr__( 'Medium 500 Italic', 'seos-magazine' ),
				'semi-bold'             => esc_attr__( 'Semi-Bold 600', 'seos-magazine' ),
				'semi-bold-italic'      => esc_attr__( 'Semi-Bold 600 Italic', 'seos-magazine' ),
				'bold'                  => esc_attr__( 'Bold 700', 'seos-magazine' ),
				'bold-italic'           => esc_attr__( 'Bold 700 Italic', 'seos-magazine' ),
				'extra-bold'            => esc_attr__( 'Extra-Bold 800', 'seos-magazine' ),
				'extra-bold-italic'     => esc_attr__( 'Extra-Bold 800 Italic', 'seos-magazine' ),
				'ultra-bold'            => esc_attr__( 'Ultra-Bold 900', 'seos-magazine' ),
				'ultra-bold-italic'     => esc_attr__( 'Ultra-Bold 900 Italic', 'seos-magazine' ),
				'invalid-value'         => esc_attr__( 'Invalid Value', 'seos-magazine' ),
				'add-new'           	=> esc_attr__( 'Add new', 'seos-magazine' ),
				'row'           		=> esc_attr__( 'row', 'seos-magazine' ),
				'limit-rows'            => esc_attr__( 'Limit: %s rows', 'seos-magazine' ),
				'open-section'          => esc_attr__( 'Press return or enter to open this section', 'seos-magazine' ),
				'back'                  => esc_attr__( 'Back', 'seos-magazine' ),
				'reset-with-icon'       => sprintf( esc_attr__( '%s Reset', 'seos-magazine' ), '<span class="dashicons dashicons-image-rotate"></span>' ),
				'text-align'            => esc_attr__( 'Text Align', 'seos-magazine' ),
				'text-transform'        => esc_attr__( 'Text Transform', 'seos-magazine' ),
				'none'                  => esc_attr__( 'None', 'seos-magazine' ),
				'capitalize'            => esc_attr__( 'Capitalize', 'seos-magazine' ),
				'uppercase'             => esc_attr__( 'Uppercase', 'seos-magazine' ),
				'lowercase'             => esc_attr__( 'Lowercase', 'seos-magazine' ),
				'initial'               => esc_attr__( 'Initial', 'seos-magazine' ),
				'select-page'           => esc_attr__( 'Select a Page', 'seos-magazine' ),
				'open-editor'           => esc_attr__( 'Open Editor', 'seos-magazine' ),
				'close-editor'          => esc_attr__( 'Close Editor', 'seos-magazine' ),
				'switch-editor'         => esc_attr__( 'Switch Editor', 'seos-magazine' ),
				'hex-value'             => esc_attr__( 'Hex Value', 'seos-magazine' ),
			);

			// Apply global changes from the kirki/config filter.
			// This is generally to be avoided.
			// It is ONLY provided here for backwards-compatibility reasons.
			// Please use the kirki/{$config_id}/l10n filter instead.
			$config = apply_filters( 'kirki/config', array() );
			if ( isset( $config['i18n'] ) ) {
				$translation_strings = wp_parse_args( $config['i18n'], $translation_strings );
			}

			// Apply l10n changes using the kirki/{$config_id}/l10n filter.
			return apply_filters( 'kirki/' . $config_id . '/l10n', $translation_strings );

		}
	}
}
