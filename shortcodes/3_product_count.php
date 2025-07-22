<?php
add_shortcode('product_count', 'product_count_shortcode');
function product_count_shortcode() {
    if (!class_exists('WooCommerce')) {
        return '<p>WooCommerce not active.</p>';
    }

    global $wpdb;
    $count = $wpdb->get_var("
        SELECT COUNT(ID)
        FROM {$wpdb->prefix}posts
        WHERE post_type = 'product'
    ");
    return "<strong>Total products:</strong> {$count}";
}
