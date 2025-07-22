<?php
add_shortcode('product_details', 'product_details_shortcode');
function product_details_shortcode() {
    if (!class_exists('WooCommerce')) {
        return '<p>WooCommerce not active.</p>';
    }

    global $wpdb;

    //Change it for the product ID you will display.
    $product_id = 69;

    if (!$product_id) return 'Invalid product ID.';
    $product = $wpdb->get_row("
        SELECT 
            p.ID AS product_id,
            p.post_title AS name,
            pm_price.meta_value AS price,
            pm_sku.meta_value AS sku,
            p.post_content AS description
        FROM {$wpdb->prefix}posts p
        LEFT JOIN {$wpdb->prefix}postmeta pm_price 
            ON p.ID = pm_price.post_id AND pm_price.meta_key = '_price'
        LEFT JOIN {$wpdb->prefix}postmeta pm_sku 
            ON p.ID = pm_sku.post_id AND pm_sku.meta_key = '_sku'
        LEFT JOIN {$wpdb->prefix}postmeta pm_description 
            ON p.ID = pm_description.post_id AND pm_description.meta_key = '_description'
        WHERE p.ID = {$product_id} AND p.post_type = 'product' AND p.post_status = 'publish'
        LIMIT 1
    ");

    if (!$product) return 'Product not found.';

    $price = $product->price !== null ? '$' . number_format($product->price, 2) : 'N/A';
    
    $output = '<div>';
    $output .= '<p><strong>ID:</strong> ' . esc_html($product->product_id) . '</p>';
    $output .= '<p><strong>Name:</strong> ' . esc_html($product->name) . '</p>';
    $output .= '<p><strong>SKU:</strong> ' . esc_html($product->sku) . '</p>';
    $output .= '<p><strong>Price:</strong> ' . esc_html($price). '</p>';
    $output .= '<p><strong>Description:</strong> ' . esc_html($product->description) . '</p>';
    $output .= '</div>';
    return $output;
}
