<?php
/**
 * Plugin Name: MSTools Price Visibility for WooCommerce
 * Plugin URI: https://github.com/mykolasamochornov/mstools-price-visibility-for-woocommerce
 * Description: Control WooCommerce price visibility. Hide prices for guests or replace them with custom text or a request form.
 * Version: 1.0.0
 * Author: Samochornov Mykola
 * Author URI: https://github.com/mykolasamochornov
 * License: GPL2+
 * Text Domain: mstools-price-visibility-for-woocommerce
 * Requires Plugins: woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'MSTPVFW_PATH' ) ) {
    define( 'MSTPVFW_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'MSTPVFW_URL' ) ) {
    define( 'MSTPVFW_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'MSTPVFW_VERSION' ) ) {
    define( 'MSTPVFW_VERSION', '1.0.0' );
}

spl_autoload_register(
    function ( string $class ): void {
        $prefix = 'MSToolsPriceVisibility\\';

        if ( strpos( $class, $prefix ) !== 0 ) {
            return;
        }

        $relative = substr( $class, strlen( $prefix ) );
        $file     = MSTPVFW_PATH . 'src/' . str_replace( '\\', '/', $relative ) . '.php';

        if ( file_exists( $file ) ) {
            require $file;
        }
    }
);

add_action('plugins_loaded',
    function (): void {
        ( new \MSToolsPriceVisibility\MSTPVFW_Plugin() )->run();
    }
);

add_filter(
    'plugin_action_links_' . plugin_basename( __FILE__ ),
    function ( array $links ): array {
        $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=mstpvfw-settings' ) ) . '">' . esc_html__( 'Settings', 'mstools-price-visibility-for-woocommerce' ) . '</a>';

        array_unshift( $links, $settings_link );

        return $links;
    }
);
