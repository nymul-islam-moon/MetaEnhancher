<?php
/**
 * Plugin Name: WP-MetaEnhancer
 * Description: A plugin to easily add SEO meta tags to posts and pages, and customize the admin footer message in the WordPress dashboard.
 * Author: Nymul Islam
 * Author URI: https://nymul-islam-moon.com/
 * Version: 0.1
 * Plugin URI: https://github.com/nymul-islam-moon/MetaEnhancher
 */

// Add the settings menu
add_action('admin_menu', 'wp_meta_enhancer_menu');
function wp_meta_enhancer_menu() {
    add_options_page('WP-MetaEnhancer Settings', 'WP-MetaEnhancer', 'manage_options', 'wp_meta_enhancer', 'wp_meta_enhancer_options');
}

function wp_meta_enhancer_options() {
    ?>
    <div class="wrap">
        <h1>WP-MetaEnhancer Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('wp_meta_enhancer_group'); ?>
            <?php do_settings_sections('wp_meta_enhancer'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'wp_meta_enhancer_settings');
function wp_meta_enhancer_settings() {
    // SEO Settings
    register_setting('wp_meta_enhancer_group', 'wp_meta_enhancer_seo_tags');
    add_settings_section('wp_meta_enhancer_seo_section', 'Default SEO Meta Tags', null, 'wp_meta_enhancer');
    add_settings_field('seo_meta_title', 'Default Title', 'seo_meta_title_callback', 'wp_meta_enhancer', 'wp_meta_enhancer_seo_section');
    add_settings_field('seo_meta_description', 'Default Description', 'seo_meta_description_callback', 'wp_meta_enhancer', 'wp_meta_enhancer_seo_section');
    add_settings_field('seo_meta_keywords', 'Default Keywords', 'seo_meta_keywords_callback', 'wp_meta_enhancer', 'wp_meta_enhancer_seo_section');

    // Footer Message Settings
    register_setting('wp_meta_enhancer_group', 'wp_meta_enhancer_footer_message');
    add_settings_section('wp_meta_enhancer_footer_section', 'Admin Footer Message', null, 'wp_meta_enhancer');
    add_settings_field('footer_message', 'Footer Message', 'footer_message_callback', 'wp_meta_enhancer', 'wp_meta_enhancer_footer_section');
}

// SEO Settings Callbacks
function seo_meta_title_callback() {
    $options = get_option('wp_meta_enhancer_seo_tags');
    echo '<input type="text" name="wp_meta_enhancer_seo_tags[title]" value="' . esc_attr($options['title']) . '">';
}

function seo_meta_description_callback() {
    $options = get_option('wp_meta_enhancer_seo_tags');
    echo '<textarea name="wp_meta_enhancer_seo_tags[description]">' . esc_attr($options['description']) . '</textarea>';
}

function seo_meta_keywords_callback() {
    $options = get_option('wp_meta_enhancer_seo_tags');
    echo '<input type="text" name="wp_meta_enhancer_seo_tags[keywords]" value="' . esc_attr($options['keywords']) . '">';
}

// Footer Message Callback
function footer_message_callback() {
    $message = get_option('wp_meta_enhancer_footer_message');
    echo '<textarea name="wp_meta_enhancer_footer_message">' . esc_attr($message) . '</textarea>';
}

// Add meta boxes to posts/pages for custom SEO meta tags
add_action('add_meta_boxes', 'wp_meta_enhancer_meta_box');
function wp_meta_enhancer_meta_box() {
    add_meta_box('wp_meta_enhancer_meta', 'SEO Meta Tags', 'wp_meta_enhancer_meta_box_callback', ['post', 'page'], 'normal', 'high');
}

function wp_meta_enhancer_meta_box_callback($post) {
    $meta = get_post_meta($post->ID, 'wp_meta_enhancer_meta_tags', true);
    ?>
    <p>
        <label for="seo_meta_title">Title</label>
        <input type="text" name="wp_meta_enhancer_meta_tags[title]" id="seo_meta_title" value="<?php echo esc_attr($meta['title']); ?>">
    </p>
    <p>
        <label for="seo_meta_description">Description</label>
        <textarea name="wp_meta_enhancer_meta_tags[description]" id="seo_meta_description"><?php echo esc_attr($meta['description']); ?></textarea>
    </p>
    <p>
        <label for="seo_meta_keywords">Keywords</label>
        <input type="text" name="wp_meta_enhancer_meta_tags[keywords]" id="seo_meta_keywords" value="<?php echo esc_attr($meta['keywords']); ?>">
    </p>
    <?php
}

add_action('save_post', 'save_wp_meta_enhancer_meta_tags');
function save_wp_meta_enhancer_meta_tags($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    $meta = $_POST['wp_meta_enhancer_meta_tags'];
    update_post_meta($post_id, 'wp_meta_enhancer_meta_tags', $meta);
}

// Output SEO meta tags in the head
add_action('wp_head', 'output_wp_meta_enhancer_meta_tags');
function output_wp_meta_enhancer_meta_tags() {
    if (is_singular()) {
        global $post;
        $meta = get_post_meta($post->ID, 'wp_meta_enhancer_meta_tags', true);
    } else {
        $meta = get_option('wp_meta_enhancer_seo_tags');
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
add_filter('admin_footer_text', 'wp_meta_enhancer_footer_message');
function wp_meta_enhancer_footer_message() {
    $message = get_option('wp_meta_enhancer_footer_message');
    if ($message) {
        echo esc_html($message);
    }
}
?>
