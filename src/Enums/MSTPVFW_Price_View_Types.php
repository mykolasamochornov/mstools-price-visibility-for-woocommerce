<?php

namespace MSToolsPriceVisibility\Enums;

if (!defined('ABSPATH')) {
	die;
}

/**
 * Enum class for price visibility modes.
 *
 * Defines how product prices should be displayed on the frontend:
 * - Without changes
 * - Hide price
 * - Hide price and show custom text
 * - Hide price and show request form
 *
 * Provides a method to get human-readable labels for UI display.
 */
final class MSTPVFW_Price_View_Types
{
	/**
	 * Private constructor to prevent instantiation.
	 */
	private function __construct() {}

	/**
	 * Display prices without any changes.
	 */
	public const WITHOUT_CHANGES = 'without_changes';

	/**
	 * Hide the product price completely.
	 */
	public const HIDE_PRICE = 'hide_price';

	/**
	 * Hide the price and display custom text instead.
	 */
	public const HIDE_PRICE_AND_SHOW_TEXT = 'hide_price_and_show_text';

	/**
	 * Hide the price and display a request price form.
	 */
	public const HIDE_PRICE_AND_SHOW_FORM_REQUEST = 'hide_price_and_show_form_request';

	/**
	 * List of all allowed values.
	 *
	 * @var string[]
	 */
	public const ALL = [
		self::WITHOUT_CHANGES,
		self::HIDE_PRICE,
		self::HIDE_PRICE_AND_SHOW_TEXT,
		self::HIDE_PRICE_AND_SHOW_FORM_REQUEST,
	];

	/**
	 * Get human-readable labels for each price view type.
	 *
	 * Useful for settings UI or dropdowns.
	 *
	 * @return array<string,string> Associative array of value => label
	 */
	public static function labels(): array
	{
		return [
			self::WITHOUT_CHANGES => __('Without changes', 'mstools-price-visibility-for-woocommerce'),
			self::HIDE_PRICE => __('Hide price', 'mstools-price-visibility-for-woocommerce'),
			self::HIDE_PRICE_AND_SHOW_TEXT => __('Hide price and show text', 'mstools-price-visibility-for-woocommerce'),
			self::HIDE_PRICE_AND_SHOW_FORM_REQUEST => __('Hide price and show request form', 'mstools-price-visibility-for-woocommerce'),
		];
	}
}
