<?php

namespace WPBN;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin loaded final class
 * 
 * @author Kamal Hosen
 * @since 1.0.0
 */

final class Base {
    /**
	 * Accesing for object of this class
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Construct function of this class
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->define_constant();
		Autoloader::run();
	}

	/**
	 * Defining constant function
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function define_constant() {

		define( 'WPBN_VERSION', '1.0.0' );
		define( 'WPBN_PACKAGE', 'free' );
		define( 'WPBN_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'WPBN_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
	}

	/**
	 * Plugin initialization function
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function init() {
		// admin menu and dashboard
		Inc\Admin\Dashboard_Manager::instance()->init();
		
		// assets
		Inc\Core\Assets_Manager::instance()->init();
		// Metabox
		Inc\Admin\Metabox_Manager::instance()->init();
		// Frontend
		Inc\Frontend\Frontend_Manager::instance()->init();
		// Shortcode
		Inc\Frontend\Shortcode_Manager::instance()->init();

		
	}

	/**
	 * singleton instance create function
	 *
	 * @return object
	 * @since 1.0.0
	 */
	public static function instance() {
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

