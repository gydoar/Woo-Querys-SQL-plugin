<?php
add_shortcode('highest_customer', 'highest_customer_shortcode');
function highest_customer_shortcode() {
    if (!class_exists('WooCommerce')) {
        return '<p>WooCommerce not active.</p>';
    }


    global $wpdb;
    $query = $wpdb->prepare("
        SELECT 
            u.display_name,
            u.user_email,
            SUM(o.total_amount) AS total_value
        FROM 
            {$wpdb->prefix}wc_orders o
            INNER JOIN {$wpdb->users} u ON o.customer_id = u.ID
        WHERE 
            o.type = 'shop_order'
            AND o.status IN ('wc-completed', 'wc-processing', 'wc-on-hold')
            AND o.customer_id > 0
        GROUP BY 
            o.customer_id, u.display_name
        ORDER BY 
            total_value DESC
        LIMIT 1
    ");
    $customer = $wpdb->get_row($query);
    if (!$customer) {
        return '<p>Not customer found.</p>';
    }

    $formatted_value = function_exists('wc_price') ? wc_price($customer->total_value) : '$' . number_format($customer->total_value, 2);

    $output = '<div>';
    $output .= '<p><strong>Name:</strong> ' . esc_html($customer->display_name) . '</p>';
    $output .= '<p><strong>Email:</strong> ' . esc_html($customer->user_email) . '</p>';
    $output .= '<p><strong>Total Value:</strong> ' . $formatted_value . '</p>';
    $output .= '</div>';
    return $output;
}
