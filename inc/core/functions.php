<?php

/**
 * Render function
 * 
 * @author Kamal Hosen
 * @since 1.0.0
 */

if( !function_exists( 'wpbn_breaking_news' )){
    function wpbn_breaking_news() {
        $frontend = new \WPBN\Inc\Frontend\Frontend_Manager();

        echo $frontend->render();
    }
}