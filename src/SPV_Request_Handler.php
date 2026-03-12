<?php

declare(strict_types=1);

namespace SmartPriceVisibility;

use SmartPriceVisibility\SPV_Settings;
use SmartPriceVisibility\Enums\SPV_Price_View_Types;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Handles price request form submissions and custom order statuses.
 *
 * Responsible for registering a custom "Price Request" order status,
 * adding it to WooCommerce order dropdowns, handling AJAX submissions
 * of price request orders, and enqueueing necessary JavaScript.
 */
class SPV_Request_Handler
{
	/**
	 * Initialize request handler hooks.
	 *
	 * Registers custom order status, WooCommerce filters, AJAX handlers,
	 * and enqueues scripts for the request form.
	 *
	 * @return void
	 */
	public function init(): void
	{
	    add_action('init', [$this, 'registerStatusAndAddToStatuses'], 20);

	    // AJAX
	    add_action('wp_ajax_spv_create_request_order', [$this, 'ajaxCreateRequestOrder']);
	    add_action('wp_ajax_nopriv_spv_create_request_order', [$this, 'ajaxCreateRequestOrder']);

	    // Enqueue scripts
	    add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
	}

	/**
	 * Register custom order status and add it to WooCommerce statuses.
	 *
	 * @return void
	 */
	public function registerStatusAndAddToStatuses(): void
	{
	    $this->registerStatus();
	    add_filter('wc_order_statuses', [$this, 'addToWooCommerceStatuses'], 20);
	}

	/**
	 * Register the custom "Price Request" order status.
	 *
	 * @return void
	 */
	public function registerStatus(): void
	{
		register_post_status('wc-request', [
			'label'                     => __('Price Request', 'smart-price-visibility'),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop(
				'Price Request <span class="count">(%s)</span>',
				'Price Requests <span class="count">(%s)</span>',
				'smart-price-visibility'
			),
		]);
	}

	/**
	 * Add the custom order status to WooCommerce order dropdown lists.
	 *
	 * @param array $statuses Existing WooCommerce order statuses
	 * @return array Modified order statuses including the custom status
	 */
	public function addToWooCommerceStatuses(array $statuses): array
	{
		$statuses['wc-request'] = __('Price Request', 'smart-price-visibility');
		return $statuses;
	}

	/**
	 * Handle AJAX request to create a price request order.
	 *
	 * Validates input, creates a WooCommerce order with the "Price Request" status,
	 * and returns JSON success or error response.
	 *
	 * @return void
	 */
	public function ajaxCreateRequestOrder(): void
	{
		check_ajax_referer('spv_nonce', 'nonce');

		$email = sanitize_email($_POST['email'] ?? '');
		$product_id = intval($_POST['product_id'] ?? 0);

		if (!$email || !$product_id) {
			wp_send_json_error(['message' => __('Invalid data', 'smart-price-visibility')]);
		}

		$product = wc_get_product($product_id);
		if (!$product) {
			wp_send_json_error(['message' => __('Product not found', 'smart-price-visibility')]);
		}

		$order = wc_create_order();
		if (is_wp_error($order)) {
			wp_send_json_error(['message' => __('Failed to create order', 'smart-price-visibility')]);
		}

		$order->add_product($product, 1);
		$order->set_billing_email($email);
		$order->update_status('wc-request');
		$order->calculate_totals();
		$order->save();

		wp_send_json_success([
			'message'  => __('Request submitted successfully', 'smart-price-visibility'),
			'order_id' => $order->get_id(),
			'status'   => $order->get_status(),
		]);
	}

	/**
	 * Enqueue JavaScript for the price request form on the frontend.
	 *
	 * Only enqueues scripts if the plugin mode requires showing the request form.
	 *
	 * @return void
	 */
	public function enqueueScripts(): void
	{
		$options = SPV_Settings::getOptions();

		if (($options['mode'] ?? '') !== SPV_Price_View_Types::HIDE_PRICE_AND_SHOW_FORM_REQUEST) {
			return;
		}

		wp_enqueue_script(
			'spv-request-form',
			SPV_URL . 'views/assets/js/spv-request-form.js',
			[],
			SPV_VERSION,
			true
		);

		wp_localize_script('spv-request-form', 'spv_ajax', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('spv_nonce'),
		]);
	}
}
