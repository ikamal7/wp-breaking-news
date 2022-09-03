<?php

namespace WPBN\Inc\Core;

// If this file is called directly, abort.
defined('ABSPATH') || exit;

class Assets_Manager {

    use \WPBN\Traits\Singleton;
    
    public function init() {
        add_action('admin_enqueue_scripts', [ $this, 'js_css_admin' ] );
        add_action('wp_enqueue_scripts', [ $this, 'js_css_public' ] );
    }

    public function js_css_public() {
        wp_enqueue_style('wpbn-main-css', WPBN_PLUGIN_URL . 'assets/css/style.css', [], WPBN_VERSION, 'all');
    }
    
    public function js_css_admin() {
        
        $screen = get_current_screen();
        // var_dump($screen->id);
        if( $screen->id == 'toplevel_page_wpbn-breaking-news-menu' ) {
            // enqueueu setup template assets
            wp_enqueue_style('wpbn-main-dashboard', WPBN_PLUGIN_URL . 'assets/admin/css/dashboard.css', [], WPBN_VERSION, 'all');

            // Scripts
            wp_enqueue_script('wpbn-main-dashboard', WPBN_PLUGIN_URL . 'assets/admin/js/dashboard.js', ['jquery'], WPBN_VERSION, true);
            
        }

        if( $screen->id == 'post' ){
           
            wp_enqueue_style('time-css', WPBN_PLUGIN_URL.'assets/admin/css/jquery.datetimepicker.min.css');
            wp_enqueue_script('timepicker-js', WPBN_PLUGIN_URL.'assets/admin/js/jquery.datetimepicker.full.min.js',[], WPBN_VERSION, true);
            wp_enqueue_script('wpbn_metabox', WPBN_PLUGIN_URL . 'assets/admin/js/metabox.js', ['jquery', 'timepicker-js'], WPBN_VERSION, true);
        }

    }
}