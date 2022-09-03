<?php

namespace WPBN\Inc\Frontend;

class Shortcode_Manager {
	use \WPBN\Traits\Singleton;

	public function init() {
		add_shortcode('wpbn_breaking_news', [ $this, 'wpbn_shortcode' ]);
	}

	public function wpbn_shortcode($atts, $content = null ) {
		return Frontend_Manager::instance()->render();
	}
}