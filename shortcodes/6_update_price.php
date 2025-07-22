<?php
add_shortcode('update_price', 'update_price_shortcode');
function update_price_shortcode() {
    if (!class_exists('WooCommerce')) {
        return '<p>WooCommerce not active.</p>';
    }


    global $wpdb;

    // Define here the fixed product ID and new price
    $product_id = 171; // change this to your product ID
    $new_price = 50; //  change this to your desired price

    $meta_keys = ['_price', '_regular_price'];
    foreach ($meta_keys as $key) {
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT meta_id FROM {$wpdb->prefix}postmeta WHERE post_id = %d AND meta_key = %s",
            $product_id, $key
        ));
        if ($exists) {
            $wpdb->update(
                $wpdb->prefix . 'postmeta',
                ['meta_value' => $new_price],
                ['post_id' => $product_id, 'meta_key' => $key]
            );
        } else {
            $wpdb->insert(
                $wpdb->prefix . 'postmeta',
                ['post_id' => $product_id, 'meta_key' => $key, 'meta_value' => $new_price]
            );
        }
    }
    $output = '<div>';
    $output .= '<p><strong>Product ID:</strong> ' . esc_html($product_id) . '</p>';
    $output .= '<p><strong>Price updated to: $</strong> ' . esc_html($new_price) . '</p>';
    $output .= '</div>';
    return $output;
}
