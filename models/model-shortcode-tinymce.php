<?php
/**
 * Add shortcode to Tinymce
 *
 * @package    Cherry_Framework
 * @subpackage Model
 * @author     Cherry Team <cherryframework@gmail.com>
 * @copyright  Copyright (c) 2012 - 2016, Cherry Team
 * @link       http://www.cherryframework.com/
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Model Shortcode_Tinymce
 */
class Shortcode_Tinymce {

	/**
	 * Add filters
	 */
	public static function tm_shortcode_button() {

		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			add_filter( 'mce_external_plugins', array( 'Shortcode_Tinymce', 'tm_add_buttons' ) );
			add_filter( 'mce_buttons', array( 'Shortcode_Tinymce', 'tm_register_buttons' ) );
		}
	}

	/**
	 * Add js file to plugins array
	 *
	 * @param  [type] $plugin_array array of plugins.
	 *
	 * @author mif32.
	 * @return [type] $plugin_array.
	 */
	public static function tm_add_buttons( $plugin_array ) {
		$plugin_array['tm_shortcodes'] = TM_REAL_ESTATE_URI . 'assets/shortcode/shortcode-tinymce-button.js';

		return $plugin_array;
	}

	/**
	 * Add buttons to buttons array
	 *
	 * @param  [type] $buttons array of buttons.
	 *
	 * @author mif32.
	 * @return [type] $buttons.
	 */
	public static function tm_register_buttons( $buttons ) {
		foreach ( Model_Main::get_shortcodes() as $key => $value ) {
			array_push( $buttons, $value );
		}

		return $buttons;
	}

	/**
	 * Prepare return all view settings
	 *
	 * @author mif32.
	 * @return [type] $view_settings.
	 */
	public static function tm_shortcode_view() {
		$view_settings[ Model_Main::SHORT_CODE_PROPERTIES ] = array(
								'title'  => __( 'Property id', 'tm-real-estate' ),
								'image'  => '',
								'width'  => 600,
								'height' => 310,
								'body'	 => array(
									array(
										'type'       => 'textbox',
										'name'       => 'property_id',
										'value'      => 0,
										'label'		 => __( 'Property id', 'tm-real-estate' ),
									),
									array(
										'type'       => 'listbox',
										'name'       => 'status',
										'label'		 => __( 'Property status', 'tm-real-estate' ),
										'values'    => array(
											array( 'text' => '', 'value' => '' ),
											array( 'text' => __( 'Rent', 'tm-real-estate' ), 'value' => 'rent' ),
											array( 'text' => __( 'Sale', 'tm-real-estate' ), 'value' => 'sale' ),
										),
									),
									array(
										'type'        => 'listbox',
										'name'        => 'tag',
										'multiple'	  => false,
										'value'       => '',
										'label'		  => __( 'Property tag', 'tm-real-estate' ),
										'values'     => Shortcode_Tinymce::tm_prepare_options( Model_Main::get_tags() ),
									),
									array(
										'type'        => 'listbox',
										'name'        => 'agent',
										'value'       => '',
										'label'  => __( 'Agent', 'tm-real-estate' ),
										'values'     => Shortcode_Tinymce::tm_prepare_options( Model_Main::get_agents() ),
									),
									array(
										'type'        => 'listbox',
										'name'        => 'type',
										'value'       => '',
										'label'  => __( 'Property type', 'tm-real-estate' ),
										'values'     => Shortcode_Tinymce::tm_prepare_options( Model_Main::get_categories() ),
									),
									array(
										'type'       => 'textbox',
										'name'       => 'limit',
										'subtype'	 => 'number',
										'value'      => 0,
										'label' => __( 'Limit', 'tm-real-estate' ),
									),
									array(
										'type'       => 'textbox',
										'subtype'	 => 'number',
										'name'       => 'offset',
										'value'      => 0,
										'label' => __( 'Offset', 'tm-real-estate' ),
									),
								),
							);
		return $view_settings;
	}

	/**
	 * Prepare option for js modal window
	 *
	 * @param  [type] $options array of options.
	 *
	 * @author mif32.
	 * @return [type] $js_options.
	 */
	public static function tm_prepare_options( $options ) {
		$js_options = array();
		$js_options[] = array( 'text' => '', 'value' => '' );
		if ( is_array( $options ) ) {
			foreach ( $options as $key => $value ) {
					$js_options[] = array( 'text' => $value, 'value' => $key );
			}
			return $js_options;
		}
	}
}
