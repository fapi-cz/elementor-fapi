<?php

declare(strict_types = 1);

use Fapi\FapiClient\FapiClientFactory;
use Fapi\FapiClient\Tools\SecurityChecker;


/**
 * Plugin Name: Elementor Fapi
 * Description: Elementor Fapi.
 * Plugin URI:  https://musilda.com/
 * Version:     1.0
 * Author:      Vladislav Musilek
 * Author URI:  https://musilda.com/
 * Text Domain: elementor-fapi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'FEMDIR', plugin_dir_path( __FILE__ ) );
define( 'FEURL', plugin_dir_url( __FILE__ ) );

require_once( 'vendor/autoload.php' );
require_once( 'includes/class-fapi-credentials.php' );
require_once( 'includes/class-fapi-forms.php' );

final class Elementor_Fapi{

	/**
	 * Plugin Version
	 *
	 * @since 1.2.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.2.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.2.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'elementor-fapi' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function init() {

		//Register styles
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );

		// Register scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'plugin.php' );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-fapi' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'elementor-fapi' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-fapi' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-fapi' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'elementor-fapi' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-fapi' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-fapi' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'elementor-fapi' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-konfigufapirator' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * widget_styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function widget_styles() {
		wp_enqueue_style( 'elementor-fapi', plugins_url( '/assets/css/main.css', __FILE__ ) );
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0
	 * @access public
	 */
	public function widget_scripts() {
		//wp_enqueue_script( 'elementor-konfigurator', plugins_url( '/assets/js/main.js', __FILE__ ), [ 'jquery' ], false, true );
	}

}

// Instantiate Elementor_Kofigurator.
new Elementor_Fapi();

//Admin settings
if( is_admin() ){
	require_once( 'admin/admin-handler.php' );
}