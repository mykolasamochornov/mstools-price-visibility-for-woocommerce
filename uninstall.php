<?php
/**
 * Uninstall script for Smart Price Visibility for WooCommerce
 *
 * Deletes plugin options from the database.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('spv_options');
