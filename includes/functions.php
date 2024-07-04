<?php

function output_meta_tags() {
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
            echo "\n<meta name=\"title\" content=\"" . esc_attr($meta['title']) . "\" />\n";
        }
        if (!empty($meta['description'])) {
            echo "\n<meta name=\"description\" content=\"" . esc_attr($meta['description']) . "\" />\n";
        }
        if (!empty($meta['keywords'])) {
            echo "\n<meta name=\"keywords\" content=\"" . esc_attr($meta['keywords']) . "\" />\n";
        }
    }
}