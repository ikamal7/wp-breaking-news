<?php
/**
 * Plugin main file.
 *
 * @package category
 */



defined( 'ABSPATH' ) || exit;

/**
 * Plugin Name:       WP Breaking News Display
 * Plugin URI:        https://kamalhosen.xyz/plugins/wp-breaking-news/
 * Description:       Display breaking news to everywhere in site.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      5.6
 * Author:            Kamal Hosen
 * Author URI:        https://kamalhosen.xyz/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       wp-breaking-news
 * Domain Path:       /i18n
 */

 // run auto loader.
require 'autoloader.php';

// run plugin initialization file.
require 'base.php';
require 'inc/core/functions.php';

add_action('plugins_loaded', [ WPBN\Base::instance(), 'init'] );
