<?php 
add_shortcode('list_products_desc', 'list_products_desc_shortcode');
function list_products_desc_shortcode() {
    if (!class_exists('WooCommerce')) {
        return '<p>WooCommerce not active.</p>';
    }


    global $wpdb;
    $products = $wpdb->get_results("
        SELECT 
            p.ID AS product_id,
            p.post_title AS name,
            pm_price.meta_value AS price,
            pm_sku.meta_value AS sku
        FROM {$wpdb->prefix}posts p
        LEFT JOIN {$wpdb->prefix}postmeta pm_price 
            ON p.ID = pm_price.post_id AND pm_price.meta_key = '_price'
        LEFT JOIN {$wpdb->prefix}postmeta pm_sku 
            ON p.ID = pm_sku.post_id AND pm_sku.meta_key = '_sku'
        WHERE p.post_type = 'product'
        ORDER BY price DESC
    ");

    if (!$products) return 'No products found.';

    $output = '<table><thead><tr><th>ID</th><th>Name</th><th>SKU</th><th>Price</th></tr></thead><tbody>';
    foreach ($products as $p) {
        $price = $p->price !== null ? '$' . number_format($p->price, 2) : 'N/A';
        $output .= "<tr><td>{$p->product_id}</td><td>{$p->name}</td><td>{$p->sku}</td><td>{$price}</td></tr>";
    }
    $output .= '</tbody></table>';
    return $output;
}
