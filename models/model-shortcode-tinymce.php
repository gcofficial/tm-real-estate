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
	public $view_settings;
	public function tm_shortcode_button() {
		if ( current_user_can ('edit_posts') && current_user_can ('edit_pages') ) {
			add_filter ('mce_external_plugins', array( 'Shortcode_Tinymce', 'tm_add_buttons' ));
			add_filter ('mce_buttons', array( 'Shortcode_Tinymce', 'tm_register_buttons' ));
		}
	}

	public function tm_add_buttons($plugin_array) {
		$plugin_array['pushortcodes'] = plugin_dir_url (__FILE__) . 'shortcode-tinymce-button.js';

		return $plugin_array;
	}

	public function tm_register_buttons($buttons) {
		//array_push ($buttons,'separator', 'pushortcodes');
		foreach ( Model_Main::get_shortcodes() as $key => $value ) {
			array_push ($buttons, $value);
		}

		return $buttons;
	}
	
	public function tm_shortcode_view(){
		$view_settings[Model_Main::SHORT_CODE_PROPERTIES] = json_encode(
					array(
							array(
								'type'       => 'textbox',
								'name'       => 'property_id',
								'value'      => 0,
								'label' => __( 'property id', 'tm-real-estate' ),
							),
							array(
								'type'       => 'listbox',
								'name'       => 'status',
								'label' => __( 'Property status', 'tm-real-estate' ),
								'values'    => array(
									array( 'text' => __( 'Rent', 'tm-real-estate' ), 'value' => 'rent' ),
									array( 'text' => __( 'Sale', 'tm-real-estate' ), 'value' => 'sale' ),
								),
							),
//							array(
//								'type'        => 'listbox',
//								'name'        => 'tag',
//								'multiple'	  => true,
//								'value'       => '',
//								'label'  => __( 'Property type', 'tm-real-estate' ),
//								'values'     => Shortcode_Tinymce::tm_prepare_options(Model_Main::get_tags())
//							),
							array(
								'type'        => 'listbox',
								'name'        => 'agent',
								'value'       => '',
								'label'  => __( 'Agent', 'tm-real-estate' ),
								'values'     => Shortcode_Tinymce::tm_prepare_options(Model_Main::get_agents())
							),
							array(
								'type'        => 'listbox',
								'name'        => 'type',
								'value'       => '',
								'label'  => __( 'Property type', 'tm-real-estate' ),
								'values'     => Shortcode_Tinymce::tm_prepare_options(Model_Main::get_categories())
							),
			)
			);
		return $view_settings;
	}
	public function tm_prepare_options ( $options ){
		$js_options = array();
		foreach ( $options as $key => $value ) {
				$js_options[] = array( 'text' => $value, 'value' => $key, );
		}
		return $js_options;
	}
}
