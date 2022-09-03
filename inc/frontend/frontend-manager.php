<?php

namespace WPBN\Inc\Frontend;

defined( 'ABSPATH' ) || exit();

class Frontend_Manager {

	use \WPBN\Traits\Singleton;

	public function init() {
		add_action('wp_head', [$this, 'inject_style_css']);
	}
	public function inject_style_css() {
		$settings = get_option('wpbn_global_settings', false);;
		?>
			<style>
				
				.wpbn_breaking_news{
					border: 1px solid <?php esc_attr_e($settings['style']['bg_color']); ?>;
				}
				.wpbn_breaking_news, .wpbn_breaking_news a {
					color: <?php esc_attr_e($settings['style']['title_color']); ?>;
					background: <?php esc_attr_e($settings['style']['bg_color']); ?>;
				}
			</style>
		<?php
		
	}
	public function query() {
		
		$meta_query = array(
			'relation' => 'OR',
			array(
				'key'     => 'wpbn_enable_news',
				'value'   => '1',
				'compare' => '='
			),
			array(
				'relation' => 'AND',
				array(
					'key'     => 'wpbn_enable_expire_time',
					'value'   => '1',
					'compare' => '='
				),
				array(
					'key'     => 'wpbn_expire_date',
					'value'   => date('Y/m/d H:i'),
					'compare' => '>=',
					'type' => 'DATE'
				),
			)
			
		);

		$query_args = [
			'post_type'=> 'post',
			'posts_per_page'=> 1,
			'meta_query'=> $meta_query,
			'orderby'=> 'modified',
			'order'=> 'DESC',
		];
		$query = new \WP_Query( $query_args );

		return $query;

	}



	public function render() {
		$settings = get_option('wpbn_global_settings', false);
		$q = $this->query();
		$html ='';
		ob_start();
		if( $q->have_posts()){
			$html .= '<div class="wpbn_breaking_news">';
			if( !empty( $settings['title'] ) ){
				$html .= '<div class="wpbn_breaking_news_header">'. esc_html($settings['title']). ': </div>';
			}
			$html .='<div class="wpbn_breaking_news_item">';
			while($q->have_posts()){
				$q->the_post();
				$post_id = get_the_ID();
				$custom_title = get_post_meta($post_id, 'wpbn_custom_title', true);
				$edit_link ='';
				if($settings['edit_link'] == '1' && current_user_can( 'edit_post', $post_id ) ){
					$edit_link = '<a class="wpbn_breaking_news_edit" href="'.get_edit_post_link($post_id).'">'.esc_html( 'Edit' ).'</a>';
				}
				
				if( !empty( $custom_title ) ){
					$title = '<a href="'. get_the_permalink() .'"> '. get_the_title() .' | '.$custom_title . ' </a>' . $edit_link;
				}else{
					$title = '<a href="'. get_the_permalink() .'">'. get_the_title() .'</a>' . $edit_link;
				}
				$html .= $title;
				
				
			}
		}
		wp_reset_postdata();
		$html .= '</div></div>';
		ob_get_clean();
		return $html;
	}

}
