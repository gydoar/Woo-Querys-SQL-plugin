<?php 
add_shortcode('customer_revenue', 'customer_revenue_shortcode');
function customer_revenue_shortcode() {
    if (!class_exists('WooCommerce')) {
        return '<p>WooCommerce not active.</p>';
    }

    global $wpdb;

    //Set customer ID here
    $customer_id = 3;

    $query = $wpdb->prepare("
        SELECT 
            u.display_name,
            u.user_email,
            SUM(o.total_amount) AS total_revenue
        FROM 
            {$wpdb->prefix}wc_orders o
            INNER JOIN {$wpdb->users} u ON o.customer_id = u.ID
        WHERE 
            o.type = 'shop_order'
            AND o.status IN ('wc-completed', 'wc-processing', 'wc-on-hold')
            AND o.customer_id = %d
        GROUP BY 
            o.customer_id, u.display_name, u.user_email
    ", $customer_id);

    $result = $wpdb->get_row($query);
    if (!$result) {
        return '<p>No data found for customer ID:<strong> ' . $customer_id . '<strong/></p>';
    }

    $formatted_revenue = function_exists('wc_price') ? wc_price($result->total_revenue) : '$' . number_format($result->total_revenue, 2);

    $output = '<div>';
    $output .= '<p><strong>Name:</strong> ' . esc_html($result->display_name) . '</p>';
    $output .= '<p><strong>Email:</strong> ' . esc_html($result->user_email) . '</p>';
    $output .= '<p><strong>Revenue:</strong> ' . $formatted_revenue . '</p>';
    $output .= '</div>';
    return $output;
}
