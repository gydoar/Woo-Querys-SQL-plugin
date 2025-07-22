<?php
/*
Plugin Name: Woo Querys SQL
Description: This is part of the second round interview for Sr. PHP Developer at SupremeOpti, basically a plugin to demonstrate SQL queries with WooCommerce in 10 exercises.
Version: 0.1
Author: Andrés Vega
Author URI: https://andvega.com/
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 6.8.2
Requires PHP: 8.2
Requires Plugins: WooCommerce
*/

if (!defined('ABSPATH')) exit;

$shortcodes_dir = __DIR__ . '/shortcodes';
if (is_dir($shortcodes_dir)) {
    foreach (glob($shortcodes_dir . '/*.php') as $file) {
        include_once $file;
    }
}
