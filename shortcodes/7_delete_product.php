<?php
add_shortcode('delete_product', 'delete_product_shortcode');
function delete_product_shortcode() {
    if (!class_exists('WooCommerce')) {
        return '<p>WooCommerce not active.</p>';
    }


    global $wpdb;
    $product_id = 197;
    $post_type = $wpdb->get_var($wpdb->prepare(
        "SELECT post_type FROM {$wpdb->prefix}posts WHERE ID = %d",
        $product_id
    ));
    if ($post_type !== 'product') {
        return "Product with ID <strong>{$product_id}</strong> not found.";
    }
    $wpdb->delete(
        $wpdb->prefix . 'postmeta',
        ['post_id' => $product_id]
    );
    $wpdb->delete(
        $wpdb->prefix . 'posts',
        ['ID' => $product_id]
    );

    return "Product ID <strong>{$product_id}</strong> has been permanently deleted.";
}
