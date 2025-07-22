<?php
add_shortcode('list_customers', 'customers_list_shortcode');
function customers_list_shortcode() {
    if (!class_exists('WooCommerce')) {
        return '<p>WooCommerce not active.</p>';
    }


    global $wpdb;
    $query = $wpdb->prepare("
        SELECT DISTINCT 
            u.display_name,
            u.user_email,
            COUNT(o.id) AS total_orders
        FROM 
            {$wpdb->prefix}wc_orders o
            INNER JOIN {$wpdb->users} u ON o.customer_id = u.ID
        WHERE 
            o.type = 'shop_order'
            AND o.status NOT IN ('wc-cancelled', 'wc-refunded', 'wc-failed')
            AND o.customer_id > 0
        GROUP BY 
            o.customer_id, u.display_name
        ORDER BY 
            total_orders DESC
    ");
    $customers = $wpdb->get_results($query);
    
    if (empty($customers)) {
        return '<p>Not customers found.</p>';
    }

    $output = '<table><thead><tr><th>Name</th><th>Email</th><th>Total Orders</th></tr></thead><tbody>';
    foreach ($customers as $customer) {
        $output .= '<tr><td>' . esc_html($customer->display_name) . '</td><td>' . esc_html($customer->user_email) . '</td><td>' . esc_html($customer->total_orders) . '</td></tr>';
    }
    $output .= '</tbody></table>';
    return $output;
}
