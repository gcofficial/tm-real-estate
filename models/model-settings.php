<?php
/**
 * Model settings class file.
 *
 * @package    Cherry_Framework
 * @subpackage Class
 * @author     Cherry Team <support@cherryframework.com>
 * @copyright  Copyright (c) 2012 - 2015, Cherry Team
 * @link       http://www.cherryframework.com/
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Model settings class
 */
class Model_Settings {

	/**
	 * Titles for diferent area unit
	 *
	 * @var array
	 */
	private static $area_unit_symbols = array(
		'meters' => 'm²',
		'feets' => 'ft²',
	);

	// Settings key option constant
	const SETTINGS_KEY = 'tm-real-estate-default-settings';

	/**
	 * Is settings created?
	 *
	 * @return boolean Yes ( true ) / No ( false )
	 */
	public static function is_created() {
		return get_option( self::SETTINGS_KEY ) !== false;
	}

	/**
	 * Remove settings
	 */
	public static function clear_settings() {
		delete_option( self::SETTINGS_KEY );
	}

	/**
	 * Get default options
	 *
	 * @return array defaults.
	 */
	public static function get_default_options() {
		$defaults = get_option( self::SETTINGS_KEY );
		if ( false === $defaults ) {
			$defaults = array(
				'tm-properties-main-settings'	=> array(
					'properties-list-page'			=> null,
					'property-item-page'			=> null,
					'properties-submission-page'	=> null,
					'agent-properties-page'			=> null,
					'area-unit'						=> 'meters',
					'сurrency-sign'					=> '$',
				),
				'tm-properties-contact-form'	=> array(
					'mail-subject'		=> __( 'New mail', 'tm-real-estate' ),
					'success-message'	=> __( 'Message send', 'tm-real-estate' ),
					'failed-message'	=> __( 'Message don`t send', 'tm-real-estate' ),
				),
				'tm-properties-submission-form'	=> array(
					'mail-subject'		=> __( 'New mail', 'tm-real-estate' ),
					'success-message'	=> __( 'Message send', 'tm-real-estate' ),
					'failed-message'	=> __( 'Message don`t send', 'tm-real-estate' ),
				),
			);
		}
		return $defaults;
	}

	/**
	 * Save options
	 *
	 * @param [type] $options to save.
	 */
	public static function set_options( $options ) {
		update_option( self::SETTINGS_KEY, $options );
	}

	/**
	 * Get main settings
	 *
	 * @return string property price.
	 */
	public static function get_main_settings() {
		return get_option( 'tm-properties-main-settings' );
	}

	/**
	 * Get are unit
	 *
	 * @return string property price.
	 */
	public static function get_area_unit_setting() {
		$main_settings = self::get_main_settings();
		return $main_settings['area-unit'];
	}

	/**
	 * Get settings for submission form
	 *
	 * @return integer id.
	 */
	public static function get_submission_form_settings() {
		return get_option( 'tm-properties-submission-form' );
	}

	/**
	 * Get settings for contact form
	 *
	 * @return string property price.
	 */
	public static function get_contact_form_settings() {
		return get_option( 'tm-properties-contact-form' );
	}

	/**
	 * Get search result page
	 *
	 * @return string property price.
	 */
	public static function get_search_result_page() {
		$main_settings	= self::get_main_settings();
		$page_id		= $main_settings['properties-search-result-page'];

		$permalink = str_replace( home_url(), './', get_permalink( $page_id ) );

		return $permalink;
	}

	/**
	 * Get single page link
	 *
	 * @return string property price.
	 */
	public static function get_search_single_page() {
		$main_settings	= self::get_main_settings();
		$page_id		= $main_settings['property-item-page'];

		$permalink = get_permalink( $page_id );

		return $permalink;
	}

	/**
	 * Get single page link
	 *
	 * @return string property price.
	 */
	public static function get_agent_properties_page() {
		$main_settings	= self::get_main_settings();
		$page_id		= $main_settings['agent-properties-page'];

		$permalink = get_permalink( $page_id );

		return $permalink;
	}

	/**
	 * Get pages list
	 *
	 * @since 1.0
	 * @return array
	 */
	public static function get_page_list() {
		$args = array(
			'sort_order'  => 'asc',
			'sort_column' => 'post_title',
			'post_type'   => 'page',
			'post_status' => 'publish',
		);

		$pages = get_pages( $args );

		$pages_list = array();
		foreach ( $pages as $page ) {
			$pages_list[ $page->ID ] = $page->post_title;
		}

		return $pages_list;
	}

	/**
	 * Get area unit options
	 *
	 * @return array options for select.
	 */
	public static function get_area_unit() {
		return array(
			'feets'		=> 'feets',
			'meters'	=> 'meters',
		);
	}

	/**
	 * Get area unit title
	 *
	 * @return string title for view.
	 */
	public static function get_area_unit_title() {
		return self::$area_unit_symbols[ self::get_area_unit_setting() ];
	}

	/**
	 * Get currency symbol
	 *
	 * @return string symbol for currency.
	 */
	public static function get_currency_symbol() {
		$main_settings	= self::get_main_settings();
		return $main_settings['сurrency-sign'];
	}


	/**
	 * Create default settings
	 */
	public static function create_settings() {
		$defaults = self::get_default_options();

		$tax         = 'property-type';
		$unique      = true;

		$commercial_slug = Cherry_Creator::term( 'Commercial', $tax )->insert( $unique )->get_term_slug();
		Cherry_Creator::term( 'Shop', $tax )->set_parent_by_slug( $commercial_slug )->insert( $unique );
		Cherry_Creator::term( 'Office', $tax )->set_parent_by_slug( $commercial_slug )->insert( $unique );

		$residential_slug = Cherry_Creator::term( 'Residential', $tax )->insert( $unique )->get_term_slug();
		Cherry_Creator::term( 'Appartment', $tax )->set_parent_by_slug( $residential_slug )->insert( $unique );
		Cherry_Creator::term( 'Appartment Building', $tax )->set_parent_by_slug( $residential_slug )->insert( $unique );
		Cherry_Creator::term( 'Villa', $tax )->set_parent_by_slug( $residential_slug )->insert( $unique );

		// Properties list
		$defaults['tm-properties-main-settings']['properties-list-page'] = Cherry_Creator::post(
			array(
				'ID'            => $defaults['tm-properties-main-settings']['properties-list-page'],
				'post_title'	=> __( 'Properties list', 'tm-real-estate' ),
				'post_content'	=> Model_Main::wrap_shortcode( Model_Main::SHORT_CODE_PROPERTIES ),
				'post_type'		=> 'page',
				'post_status'	=> 'publish',
			)
		);

		// Property
		$defaults['tm-properties-main-settings']['property-item-page'] = Cherry_Creator::post(
			array(
				'ID'            => $defaults['tm-properties-main-settings']['property-item-page'],
				'post_title'	=> __( 'Property item', 'tm-real-estate' ),
				'post_content'	=> Model_Main::wrap_shortcode( Model_Main::SHORT_CODE_PROPERTY ),
				'post_type'		=> 'page',
				'post_status'	=> 'publish',
			)
		);

		// Search result page
		$defaults['tm-properties-main-settings']['properties-search-result-page'] = Cherry_Creator::post(
			array(
				'ID'            => $defaults['tm-properties-main-settings']['properties-search-result-page'],
				'post_title'	=> __( 'Search result', 'tm-real-estate' ),
				'post_content'	=> Model_Main::wrap_shortcode( Model_Main::SHORT_CODE_SEARCH_RESULT ),
				'post_type'		=> 'page',
				'post_status'	=> 'publish',
			)
		);

		// Search result page
		$defaults['tm-properties-main-settings']['properties-submission-page'] = Cherry_Creator::post(
			array(
				'ID'            => $defaults['tm-properties-main-settings']['properties-submission-page'],
				'post_title'	=> __( 'Submission form', 'tm-real-estate' ),
				'post_content'	=> Model_Main::wrap_shortcode( Model_Main::SHORT_CODE_SUBMISSION_FORM ),
				'post_type'		=> 'page',
				'post_status'	=> 'publish',
			)
		);

		// Agent properties page
		$defaults['tm-properties-main-settings']['agent-properties-page'] = Cherry_Creator::post(
			array(
				'ID'            => $defaults['tm-properties-main-settings']['agent-properties-page'],
				'post_title'	=> __( 'Agent properties', 'tm-real-estate' ),
				'post_content'	=> Model_Main::wrap_shortcode( Model_Main::SHORT_CODE_AGENT_PROPERTIES ),
				'post_type'		=> 'page',
				'post_status'	=> 'publish',
			)
		);

		foreach ( $defaults as $option => $newvalue ) {
			update_option( $option, $newvalue );
		}

		self::set_options( $defaults );
	}
}
