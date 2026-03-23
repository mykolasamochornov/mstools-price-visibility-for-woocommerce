<?php

declare(strict_types=1);

namespace PricePilotVisibility;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Handles all admin-related functionality for the PricePilot Visibility plugin.
 *
 * Responsible for registering admin menus, enqueueing scripts,
 * and rendering the plugin settings page.
 */
class PPVFW_Admin
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
			'ppvfw-settings',
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
		include PPVFW_PATH . 'views/admin/settings-page.php';
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
			'ppvfw-settings',
			PPVFW_URL . 'views/assets/js/ppvfw-settings.js',
			['jquery'],
			'1.0',
			true
		);

		wp_localize_script('ppvfw-settings', 'ppvfw_ajax', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ppvfw_nonce')
		]);
	}
}
