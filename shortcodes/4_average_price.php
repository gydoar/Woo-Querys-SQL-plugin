<?php
add_shortcode('average_price', 'average_price_shortcode');
function average_price_shortcode() {
    if (!class_exists('WooCommerce')) {
        return '<p>WooCommerce not active.</p>';
    }


    global $wpdb;
    $average = $wpdb->get_var("
        SELECT AVG(CAST(pm.meta_value AS DECIMAL(10,2)))
        FROM {$wpdb->prefix}postmeta pm
        INNER JOIN {$wpdb->prefix}posts p ON pm.post_id = p.ID
        WHERE pm.meta_key = '_price'
          AND p.post_type = 'product'
          AND pm.meta_value != ''
    ");

    if ($average === null) return 'No prices found.';

    return '<strong>Average price published products:</strong> $' . number_format($average, 2);
}
