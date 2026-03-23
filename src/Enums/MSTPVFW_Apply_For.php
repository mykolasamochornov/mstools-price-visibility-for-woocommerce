<?php

namespace MSToolsPriceVisibility\Enums;

if (!defined('ABSPATH')) {
	die;
}

/**
 * Enum class for applying price visibility rules.
 *
 * Defines which user types the plugin rules should apply to:
 * - Everyone
 * - Guests only
 *
 * Provides a method to get human-readable labels for UI display.
 */
final class MSTPVFW_Apply_For
{
	/**
	 * Private constructor to prevent instantiation.
	 */
	private function __construct() {}

	/**
	 * Apply rules for everyone (all users).
	 */
	public const EVERYONE = 'everyone';

	/**
	 * Apply rules only for guests (not logged-in users).
	 */
	public const GUESTS_ONLY = 'guests_only';

	/**
	 * List of all allowed values.
	 *
	 * @var string[]
	 */
	public const ALL = [
		self::EVERYONE,
		self::GUESTS_ONLY,
	];

	/**
	 * Get human-readable labels for the apply_for values.
	 *
	 * Useful for displaying in settings or UI dropdowns.
	 *
	 * @return array<string,string> Associative array of value => label
	 */
	public static function labels(): array
	{
		return [
			self::EVERYONE => __('Apply for everyone', 'mstools-price-visibility-for-woocommerce'),
			self::GUESTS_ONLY => __('Apply only for guests', 'mstools-price-visibility-for-woocommerce'),
		];
	}
}
