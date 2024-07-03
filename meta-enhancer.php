<?php
/**
 * Plugin Name: MetaEnhancer
 * Description: A plugin to easily add SEO meta tags to posts and pages, and customize the admin footer message in the WordPress dashboard.
 * Author: Nymul Islam
 * Author URI: https://nymul-islam-moon.com/
 * Version: 0.1
 * Plugin URI: https://github.com/nymul-islam-moon/MetaEnhancer
 */


// Add the settings menu
add_action('admin_menu', 'metaenhancer_menu');
function metaenhancer_menu() {
    add_options_page('MetaEnhancer Settings', 'MetaEnhancer', 'manage_options', 'metaenhancer', 'metaenhancer_options');
}

function metaenhancer_options() {
    ?>
    <div class="wrap">
        <h1>MetaEnhancer Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('metaenhancer_group'); ?>
            <?php do_settings_sections('metaenhancer'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register settings
add_action('admin_init', 'metaenhancer_settings');
function metaenhancer_settings() {
    // SEO Settings
    register_setting('metaenhancer_group', 'metaenhancer_seo_tags');
    add_settings_section('metaenhancer_seo_section', 'Default SEO Meta Tags', null, 'metaenhancer');
    add_settings_field('seo_meta_title', 'Default Title', 'seo_meta_title_callback', 'metaenhancer', 'metaenhancer_seo_section');
    add_settings_field('seo_meta_description', 'Default Description', 'seo_meta_description_callback', 'metaenhancer', 'metaenhancer_seo_section');
    add_settings_field('seo_meta_keywords', 'Default Keywords', 'seo_meta_keywords_callback', 'metaenhancer', 'metaenhancer_seo_section');

    // Footer Message Settings
    register_setting('metaenhancer_group', 'metaenhancer_footer_message');
    add_settings_section('metaenhancer_footer_section', 'Admin Footer Message', null, 'metaenhancer');
    add_settings_field('footer_message', 'Footer Message', 'footer_message_callback', 'metaenhancer', 'metaenhancer_footer_section');
}

// SEO Settings Callbacks
function seo_meta_title_callback() {
    $options = get_option('metaenhancer_seo_tags');
    echo '<input type="text" name="metaenhancer_seo_tags[title]" value="' . esc_attr($options['title']) . '">';
}

function seo_meta_description_callback() {
    $options = get_option('metaenhancer_seo_tags');
    echo '<textarea name="metaenhancer_seo_tags[description]">' . esc_attr($options['description']) . '</textarea>';
}

function seo_meta_keywords_callback() {
    $options = get_option('metaenhancer_seo_tags');
    echo '<input type="text" name="metaenhancer_seo_tags[keywords]" value="' . esc_attr($options['keywords']) . '">';
}

// Footer Message Callback
function footer_message_callback() {
    $message = get_option('metaenhancer_footer_message');
    echo '<textarea name="metaenhancer_footer_message">' . esc_attr($message) . '</textarea>';
}

// Add meta boxes to posts/pages for custom SEO meta tags
add_action('add_meta_boxes', 'metaenhancer_meta_box');
function metaenhancer_meta_box() {
    add_meta_box('metaenhancer_meta', 'SEO Meta Tags', 'metaenhancer_meta_box_callback', ['post', 'page'], 'normal', 'high');
}

function metaenhancer_meta_box_callback($post) {
    $meta = get_post_meta($post->ID, 'metaenhancer_meta_tags', true);
    ?>
    <p>
        <label for="seo_meta_title">Title</label>
        <input type="text" name="metaenhancer_meta_tags[title]" id="seo_meta_title" value="<?php echo esc_attr($meta['title']); ?>">
    </p>
    <p>
        <label for="seo_meta_description">Description</label>
        <textarea name="metaenhancer_meta_tags[description]" id="seo_meta_description"><?php echo esc_attr($meta['description']); ?></textarea>
    </p>
    <p>
        <label for="seo_meta_keywords">Keywords</label>
        <input type="text" name="metaenhancer_meta_tags[keywords]" id="seo_meta_keywords" value="<?php echo esc_attr($meta['keywords']); ?>">
    </p>
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
    $meta = $_POST['metaenhancer_meta_tags'];
    update_post_meta($post_id, 'metaenhancer_meta_tags', $meta);
}

// Output SEO meta tags in the head
add_action('wp_head', 'output_metaenhancer_meta_tags');
function output_metaenhancer_meta_tags() {
    if (is_singular()) {
        global $post;
        $meta = get_post_meta($post->ID, 'metaenhancer_meta_tags', true);
    } else {
        $meta = get_option('metaenhancer_seo_tags');
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
    $message = get_option('metaenhancer_footer_message');
    if ($message) {
        echo esc_html($message);
    }
}
?>
