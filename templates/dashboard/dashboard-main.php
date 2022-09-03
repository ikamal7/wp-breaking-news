<?php
$styles = isset( $settings['style'] ) ? $settings['style'] : [];

?>
<div class="wpbn-main-wrapper">
    <div class="wpbn-header">
        <h1><?php esc_html_e('WP Breaking News', 'wp-breaking-news'); ?></h1>
        <p><?php esc_html_e('Shortcode:'); ?> <code><?php esc_attr_e('[wpbn_breaking_news]'); ?></code></p>
        <p><?php esc_html_e('PHP function:'); ?> <code><?php esc_attr_e('<?php wpbn_breaking_news(); ?>'); ?></code></p>
    </div>
    <div class="wpbn-body">
        <div class="wpbn-form">
            <form method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="save_wpbn_option">
                <?php wp_nonce_field('wpbn_nonce', 'wpbn_nonce'); ?>

                <div class="wpbn-form-input-group">
                    <label class="wpbn-form-input-label" for="wpbn_title"><?php esc_html_e( 'Title', 'wp-breaking-news'); ?></label>
                    <input class="regular-text form-control" type="text" name="title" id="wpbn_title" value="<?php echo esc_attr(isset($settings['title']) ? $settings['title'] : 'Breaking News'); ?>">
                </div>

                <div class="wpbn-form-input-group">
                    <label class="wpbn-form-input-label" for="title-color"><?php esc_attr_e( 'Title color', 'wp-breaking-news' ); ?></label>
                    <input type="color" name="style[title_color]" id="title-color" value="<?php echo esc_attr(isset($styles['title_color']) ? $styles['title_color'] : '#FFFFFF'); ?>">
                </div>

                <div class="wpbn-form-input-group">
                    <label class="wpbn-form-input-label" for="bg-color"><?php esc_attr_e( 'Background color', 'wp-breaking-news' ); ?></label>
                    <input type="color" name="style[bg_color]" id="bg-color" value="<?php echo esc_attr(isset($styles['bg_color']) ? $styles['bg_color'] : '#000000'); ?>">
                </div>
                <div class="wpbn-form-input-group">
                    <label class="wpbn-form-input-label" for="edit-link"><?php esc_attr_e( 'Display Edit Link', 'wp-breaking-news') ?></label>
                    <input class="wpbn-switch" type="checkbox" id="edit-link" name="edit_link" value="1" <?php checked($settings['edit_link'], '1'); ?> />
                </div>
                <div class="wpbn-form-input-group">
                    <button class="btn btn-primary-blue" type="submit"><?php esc_html_e('Save Changes', 'magic-social-share'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
