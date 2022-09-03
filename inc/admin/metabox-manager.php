<?php
namespace WPBN\Inc\Admin;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit();

class Metabox_Manager {

    use \WPBN\Traits\Singleton;

	private $screens = array(
		'post',
	);

    public function metabox_fields() {

        $fields = array(
            array(
                'id' => 'enable_news',
                'label' => 'Make this post breaking news',
                'type' => 'checkbox',
            ),
            array(
                'id' => 'custom_title',
                'label' => 'Custom Title',
                'type' => 'text',
            ),
            array(
                'id' => 'enable_expire_time',
                'label' => 'Enable Expire',
                'type' => 'checkbox',
            ),
            array(
                'id' => 'expire_date',
                'label' => 'Expire Date',
                'type' => 'text',
            ),
        );

        return apply_filters('wpbn/metabox_fields', $fields);
    }

	/**
	 * Adds actions to their respective WordPress hooks.
	 */
	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'breaking-news',
				__( 'BREAKING NEWS', 'wp-breaking-news' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'side',
				'high'
			);
		}
	}

	/**
	 * Generates the HTML for the meta box
	 * 
	 * @param object $post WordPress post object
	 */
	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'breaking_news_data', 'breaking_news_nonce' );
		$this->generate_fields( $post );
	}

	/**
	 * Generates the field's HTML for the meta box.
	 */
	public function generate_fields( $post ) {
		$fields = $this->metabox_fields();
		$output = '';
		foreach ( $fields as $field ) {
			$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			$db_value = get_post_meta( $post->ID, 'wpbn_' . $field['id'], true );
			switch ( $field['type'] ) {
				case 'checkbox':
					$input = sprintf(
						'<input %s id="%s" name="%s" type="checkbox" value="1">',
						$db_value === '1' ? 'checked' : '',
						$field['id'],
						$field['id']
					);
					break;
				default:
					$input = sprintf(
						'<input id="%s" name="%s" type="%s" value="%s">',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
			}
			if($db_value){}
			$output .= '<div class="form-group-'.$field['id'].'">' . $label . '<br>' . $input . '</div>';
		}
		echo $output;
	}

	/**
	 * Hooks into WordPress' save_post function
	 */
	public function save_post( $post_id ) {
		$fields = $this->metabox_fields();
		if ( ! isset( $_POST['breaking_news_nonce'] ) )
			return $post_id;

		$nonce = $_POST['breaking_news_nonce'];
		if ( !wp_verify_nonce( $nonce, 'breaking_news_data' ) ){
			return $post_id;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			return $post_id;
		}

		foreach ( $fields as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'text':
						$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
						break;
				}
				update_post_meta( $post_id, 'wpbn_' . $field['id'], $_POST[ $field['id'] ] );
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, 'wpbn_' . $field['id'], '0' );
			}
		}
	}
}
