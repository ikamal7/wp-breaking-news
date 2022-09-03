<?php

namespace WPBN\Inc\Admin;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit();

class Dashboard_Manager {

	use \WPBN\Traits\Singleton;

	private $settings_key = 'wpbn_global_settings';

	public $template_path = WPBN_PLUGIN_DIR .'templates/';

	public function init() {
		add_action( 'admin_menu', [ $this, 'wpbn_admin_menu' ] );
		add_action('admin_post_save_wpbn_option', [$this, 'update_global_settings']);
		$this->save_initial_settings();
	}

	public function wpbn_admin_menu() {
		add_menu_page(
			esc_html__('WP Breaking News'),
			esc_html__('Breaking News'),
			'manage_options',
			'wpbn-breaking-news-menu',
			[$this, 'wpbn_dashboard_template'],
			'dashicons-share',
			3
		);
	}

	public function wpbn_dashboard_template() {
		$settings = get_option( $this->settings_key, false);
		
		echo "<div class='wrap'>";
		include_once $this->template_path . 'dashboard/dashboard-main.php';
		echo "</div>";
	}

	public function save_initial_settings() {
		$settings = [];

		if(!$this->get_global_settings()){
			$settings = [
				'title' => esc_html('Breaking News'),
				'style' => [
					'title_color' => '#000000',
					'bg_color' => '#FFFFFF',
				],
				'edit_link'=> '0'
			];
			update_option($this->settings_key, $settings);
		}
	}

	public function update_global_settings() {
		$settings = get_option($this->settings_key, false);

		if (!wp_verify_nonce($_POST['wpbn_nonce'], 'wpbn_nonce')) {
			return;
		}

		if(isset($_POST['title'])){
			$settings['title'] = sanitize_text_field($_POST['title']);
		}
		if(isset($_POST['style']['title_color'])){
			$settings['style']['title_color'] = sanitize_hex_color($_POST['style']['title_color']);
		}
		if(isset($_POST['style']['bg_color'])){
			$settings['style']['bg_color'] = sanitize_hex_color($_POST['style']['bg_color']);
		}

		if(isset($_POST['edit_link'])){
			$settings['edit_link'] = filter_var($_POST['edit_link'], FILTER_SANITIZE_NUMBER_INT);
		}else{
			$settings['edit_link'] = filter_var('0', FILTER_SANITIZE_NUMBER_INT);
		}
	
		$status = update_option($this->settings_key, $settings);

		wp_safe_redirect(admin_url('admin.php?page=wpbn-breaking-news-menu'));
		exit();
	}
	public function get_global_settings() {
		$settings = get_option($this->settings_key, false);
		return $settings;
	}
}