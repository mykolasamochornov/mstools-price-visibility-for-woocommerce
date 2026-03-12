<?php
/**
 * Plugin Name: Smart Price Visibility for WooCommerce
 * Plugin URI: https://example.com
 * Description: Control WooCommerce price visibility. Hide prices for guests or replace them with custom text or a request form.
 * Version: 1.0.0
 * Author: Samochornov Mykola
 * Author URI: https://github.com/mykolasamochornov
 * License: GPL2+
 * Text Domain: smart-price-visibility
 * Requires Plugins: woocommerce
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SPV_PATH', plugin_dir_path(__FILE__));
define('SPV_URL', plugin_dir_url(__FILE__));
define('SPV_VERSION', '1.0.0');

spl_autoload_register(function (string $class): void {
    $prefix = 'SmartPriceVisibility\\';

    if (strpos($class, $prefix) !== 0) {
        return;
    }

    $relative = substr($class, strlen($prefix));

    $file = SPV_PATH . 'src/' . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

add_action('plugins_loaded', function (): void {
    (new \SmartPriceVisibility\SPV_Plugin())->run();
});

add_filter(
    'plugin_action_links_' . plugin_basename(__FILE__),
    function (array $links): array {
        $settingsLink = '<a href="' . admin_url('admin.php?page=spv-settings') . '">Settings</a>';

        array_unshift($links, $settingsLink);

        return $links;
    }
);
