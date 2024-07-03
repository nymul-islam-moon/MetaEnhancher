<?php
/**
 * Plugin Name:        Meta Enhancer
 * Description:        Easily add SEO meta tags to posts and pages, and customize the admin footer message.
 * Author:             Nymul Islam
 * Author URI:         https://nymul-islam-moon.com/
 * Version:            1.0.1
 * License:            GPL-2.0-or-later
 * Requires at least:  6.4
 * Requires PHP:       7.4
 * Plugin URI:         https://github.com/nymul-islam-moon/MetaEnhancher
 * Stable tag:         1.0.1
 */


// Add the settings menu
add_action('admin_menu', 'meta_enhancer_menu');
function meta_enhancer_menu() {
    add_options_page('MetaEnhancer Settings', 'MetaEnhancer', 'manage_options', 'meta_enhancer', 'meta_enhancer_options');
}

function meta_enhancer_options() {
    ?>
    <div class="wrap">
        <h1>MetaEnhancer Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('meta_enhancer_group'); ?>
            <?php do_settings_sections('meta_enhancer'); ?>
            <?php wp_nonce_field('meta_enhancer_save_settings', 'meta_enhancer_nonce'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'meta_enhancer_settings');
function meta_enhancer_settings() {
    // SEO Settings
    register_setting('meta_enhancer_group', 'meta_enhancer_seo_tags');
    add_settings_section('meta_enhancer_seo_section', 'Default SEO Meta Tags', null, 'meta_enhancer');
    add_settings_field('seo_meta_title', 'Default Title', 'seo_meta_title_callback', 'meta_enhancer', 'meta_enhancer_seo_section');
    add_settings_field('seo_meta_description', 'Default Description', 'seo_meta_description_callback', 'meta_enhancer', 'meta_enhancer_seo_section');
    add_settings_field('seo_meta_keywords', 'Default Keywords', 'seo_meta_keywords_callback', 'meta_enhancer', 'meta_enhancer_seo_section');

    // Footer Message Settings
    register_setting('meta_enhancer_group', 'wp_meta_enhancer_footer_message');
    add_settings_section('meta_enhancer_footer_section', 'Admin Footer Message', null, 'meta_enhancer');
    add_settings_field('footer_message', 'Footer Message', 'footer_message_callback', 'meta_enhancer', 'meta_enhancer_footer_section');
}

// SEO Settings Callbacks
function seo_meta_title_callback() {
    $options = get_option('meta_enhancer_seo_tags');
    $title = isset($options['title']) ? esc_attr($options['title']) : '';
    echo '<input type="text" name="meta_enhancer_seo_tags[title]" value="' . esc_attr($title) . '">';
}

function seo_meta_description_callback() {
    $options = get_option('meta_enhancer_seo_tags');
    $description = isset($options['description']) ? esc_attr($options['description']) : '';
    echo '<textarea name="meta_enhancer_seo_tags[description]">' . esc_textarea($description) . '</textarea>';
}

function seo_meta_keywords_callback() {
    $options = get_option('meta_enhancer_seo_tags');
    $keywords = isset($options['keywords']) ? esc_attr($options['keywords']) : '';
    echo '<input type="text" name="meta_enhancer_seo_tags[keywords]" value="' . esc_attr($keywords) . '">';
}

// Footer Message Callback
function footer_message_callback() {
    $message = get_option('wp_meta_enhancer_footer_message');
    $message = $message ? esc_attr($message) : '';
    echo '<textarea name="wp_meta_enhancer_footer_message">' . esc_textarea($message) . '</textarea>';
}

// Add meta boxes to posts/pages for custom SEO meta tags
add_action('add_meta_boxes', 'metaenhancer_meta_box');
function metaenhancer_meta_box() {
    add_meta_box('metaenhancer_meta', 'SEO Meta Tags', 'metaenhancer_meta_box_callback', ['post', 'page'], 'normal', 'high');
}

function metaenhancer_meta_box_callback($post) {
    $meta = get_post_meta($post->ID, 'metaenhancer_meta_tags', true);
    $title = isset($meta['title']) ? esc_attr($meta['title']) : '';
    $description = isset($meta['description']) ? esc_attr($meta['description']) : '';
    $keywords = isset($meta['keywords']) ? esc_attr($meta['keywords']) : '';
    ?>
    <p>
        <label for="seo_meta_title">Title</label>
        <input type="text" name="metaenhancer_meta_tags[title]" id="seo_meta_title" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
        <label for="seo_meta_description">Description</label>
        <textarea name="metaenhancer_meta_tags[description]" id="seo_meta_description"><?php echo esc_textarea($description); ?></textarea>
    </p>
    <p>
        <label for="seo_meta_keywords">Keywords</label>
        <input type="text" name="metaenhancer_meta_tags[keywords]" id="seo_meta_keywords" value="<?php echo esc_attr($keywords); ?>">
    </p>
    <?php wp_nonce_field('save_metaenhancer_meta_tags', 'metaenhancer_meta_tags_nonce'); ?>
    <?php
}


add_action('save_post', 'save_metaenhancer_meta_tags');
function save_metaenhancer_meta_tags($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    if (!isset($_POST['metaenhancer_meta_tags_nonce']) || !wp_verify_nonce($_POST['metaenhancer_meta_tags_nonce'], 'save_metaenhancer_meta_tags')) {
        return $post_id;
    }

    if (isset($_POST['metaenhancer_meta_tags'])) {
        $meta = $_POST['metaenhancer_meta_tags'];
        // Ensure all values are properly sanitized before saving
        $meta = array_map('sanitize_text_field', $meta);
        update_post_meta($post_id, 'metaenhancer_meta_tags', $meta);
    }
}

// Output SEO meta tags in the head
// Output SEO meta tags in the head
add_action('wp_head', 'output_metaenhancer_meta_tags');
function output_metaenhancer_meta_tags() {
    $meta = null;

    if (is_singular()) {
        global $post;
        $meta = get_post_meta($post->ID, 'metaenhancer_meta_tags', true);
    }

    // Fallback to default meta tags if not set on a singular post/page
    if (empty($meta) || !is_array($meta)) {
        $meta = get_option('meta_enhancer_seo_tags');
    }

    if ($meta) {
        if (!empty($meta['title'])) {
            echo '<meta name="title" content="' . esc_attr($meta['title']) . '">';
        }
        if (!empty($meta['description'])) {
            echo '<meta name="description" content="' . esc_attr($meta['description']) . '">';
        }
        if (!empty($meta['keywords'])) {
            echo '<meta name="keywords" content="' . esc_attr($meta['keywords']) . '">';
        }
    }
}


// Customize the admin footer message
add_filter('admin_footer_text', 'metaenhancer_footer_message');
function metaenhancer_footer_message() {
    $message = get_option('wp_meta_enhancer_footer_message');
    if ($message) {
        echo esc_html($message);
    }
}