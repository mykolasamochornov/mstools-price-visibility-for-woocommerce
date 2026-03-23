<?php

declare(strict_types=1);

namespace PricePilotVisibility;

use PricePilotVisibility\PPVFW_Settings;
use PricePilotVisibility\Enums\PPVFW_Price_View_Types;

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
class PPVFW_Request_Handler
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
	    add_action('wp_ajax_ppvfw_create_request_order', [$this, 'ajaxCreateRequestOrder']);
	    add_action('wp_ajax_nopriv_ppvfw_create_request_order', [$this, 'ajaxCreateRequestOrder']);

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
		register_post_status('wc-ppvfw-request', [
			'label'                     => __('Price Request', 'price-pilot-visibility-for-woocommerce'),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			// translators: %s is the number of price request orders
			'label_count'               => _n_noop(
				'Price Request <span class="count">(%s)</span>',
				'Price Requests <span class="count">(%s)</span>',
				'price-pilot-visibility-for-woocommerce'
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
		$statuses['wc-ppvfw-request'] = __('Price Request', 'price-pilot-visibility-for-woocommerce');
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
		check_ajax_referer('ppvfw_nonce', 'nonce');

		$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
		$product_id = intval($_POST['product_id'] ?? 0);

		if (!$email || !$product_id) {
			wp_send_json_error(['message' => __('Invalid data', 'price-pilot-visibility-for-woocommerce')]);
		}

		$product = wc_get_product($product_id);
		if (!$product) {
			wp_send_json_error(['message' => __('Product not found', 'price-pilot-visibility-for-woocommerce')]);
		}

		$order = wc_create_order();
		if (is_wp_error($order)) {
			wp_send_json_error(['message' => __('Failed to create order', 'price-pilot-visibility-for-woocommerce')]);
		}

		$order->add_product($product, 1);
		$order->set_billing_email($email);
		$order->update_status('ppvfw-request');
		$order->calculate_totals();
		$order->save();

		wp_send_json_success([
			'message'  => __('Request submitted successfully', 'price-pilot-visibility-for-woocommerce'),
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
		$options = PPVFW_Settings::getOptions();

		if (($options['mode'] ?? '') !== PPVFW_Price_View_Types::HIDE_PRICE_AND_SHOW_FORM_REQUEST) {
			return;
		}

		wp_enqueue_script(
			'ppvfw-request-form',
			PPVFW_URL . 'views/assets/js/ppvfw-request-form.js',
			[],
			PPVFW_VERSION,
			true
		);

		wp_localize_script('ppvfw-request-form', 'ppvfw_ajax', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('ppvfw_nonce'),
		]);
	}
}
