<?php
/**
 * Uninstall script for MSTools Price Visibility for WooCommerce
 *
 * Deletes plugin options from the database.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('mstpvfw_options');
