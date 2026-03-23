<?php

declare(strict_types=1);

namespace MSToolsPriceVisibility;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Handles all admin-related functionality for the MSTools Price Visibility plugin.
 *
 * Responsible for registering admin menus, enqueueing scripts,
 * and rendering the plugin settings page.
 */
class MSTPVFW_Admin
{
	/**
	 * Initialize admin hooks.
	 *
	 * Adds actions for admin menu registration and script enqueueing.
	 *
	 * @return void
	 */
	public function init(): void
	{
		add_action('admin_menu', [$this, 'registerMenu'], 999);
		add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
	}

	/**
	 * Register the plugin submenu under WooCommerce menu.
	 *
	 * @return void
	 */
	public function registerMenu(): void
	{
		add_submenu_page(
			'woocommerce',
			'Price Visibility',
			'Price Visibility',
			'manage_options',
			'mstpvfw-settings',
			[$this, 'settingsPage']
		);
	}

	/**
	 * Render the settings page for the plugin.
	 *
	 * Includes the PHP template located at views/admin/settings-page.php.
	 *
	 * @return void
	 */
	public function settingsPage(): void
	{
		include MSTPVFW_PATH . 'views/admin/settings-page.php';
	}

	/**
	 * Enqueue admin scripts for the plugin settings page.
	 *
	 * Registers JavaScript files and localizes AJAX URL and nonce for security.
	 *
	 * @return void
	 */
	public function enqueueScripts(): void
	{
		wp_enqueue_script(
			'mstpvfw-settings',
			MSTPVFW_URL . 'views/assets/js/mstpvfw-settings.js',
			['jquery'],
			MSTPVFW_VERSION,
			true
		);

		wp_localize_script('mstpvfw-settings', 'mstpvfw_ajax', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('mstpvfw_nonce'),
		]);

		wp_enqueue_script(
			'mstpvfw-settings-toggle',
			MSTPVFW_URL . 'views/assets/js/mstpvfw-settings-toggle.js',
			['jquery', 'mstpvfw-settings'],
			MSTPVFW_VERSION,
			true
		);

		wp_localize_script('mstpvfw-settings-toggle', 'mstpvfwSettings', [
			'hidePriceAndShowText' => \MSToolsPriceVisibility\Enums\MSTPVFW_Price_View_Types::HIDE_PRICE_AND_SHOW_TEXT,
			'hidePriceAndShowFormRequest' => \MSToolsPriceVisibility\Enums\MSTPVFW_Price_View_Types::HIDE_PRICE_AND_SHOW_FORM_REQUEST,
		]);
	}
}
