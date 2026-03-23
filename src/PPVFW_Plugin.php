<?php

declare(strict_types=1);

namespace PricePilotVisibility;

use PricePilotVisibility\PPVFW_Admin;
use PricePilotVisibility\PPVFW_Price_Handler;
use PricePilotVisibility\PPVFW_Request_Handler;
use PricePilotVisibility\PPVFW_Settings;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Main plugin class for PricePilot Visibility.
 * 
 * Handles initialization of admin settings, price handling, request handling,
 * and general plugin setup. Also checks if WooCommerce is active.
 */
class PPVFW_Plugin
{
	/**
	 * Admin handler instance.
	 *
	 * @var PPVFW_Admin|null
	 */
	private $admin = null;

	/**
	 * Price handler instance.
	 *
	 * @var PPVFW_Price_Handler|null
	 */
	private $priceHandler = null;

	/**
	 * Settings handler instance.
	 *
	 * @var PPVFW_Settings|null
	 */
	private $settings = null;

	/**
	 * Request handler instance.
	 *
	 * @var PPVFW_Request_Handler|null
	 */
	private $requestHandler = null;

	/**
	 * Run the plugin.
	 *
	 * Initializes plugin components if WooCommerce is active,
	 * otherwise shows an admin notice.
	 *
	 * @return void
	 */
	public function run(): void
	{
		if (!class_exists('WooCommerce')) {
			add_action('admin_notices', [$this, 'woocommerceMissingNotice']);
			return;
		}

		$this->admin = new PPVFW_Admin();
		$this->priceHandler = new PPVFW_Price_Handler();
		$this->settings = new PPVFW_Settings();
		$this->requestHandler = new PPVFW_Request_Handler();

		add_action('init', [$this, 'initClasses']);
	}

	/**
	 * Initialize all plugin classes.
	 *
	 * Calls the init method on admin, price, settings, and request handlers.
	 *
	 * @return void
	 */
	public function initClasses(): void
	{
		$this->admin->init();
		$this->priceHandler->init();
		$this->settings->init();
		$this->requestHandler->init();
	}

	/**
	 * Display admin notice if WooCommerce is not installed or active.
	 *
	 * @return void
	 */
	public function woocommerceMissingNotice(): void
	{
		if (!current_user_can('activate_plugins')) return;

		echo '<div class="notice notice-error"><p>';
		echo esc_html__('PricePilot Visibility for WooCommerce requires WooCommerce to be installed and active.', 'price-pilot-visibility-for-woocommerce');
		echo '</p></div>';
	}
}
