<?php

declare(strict_types=1);

namespace SmartPriceVisibility;

use SmartPriceVisibility\Enums\SPV_Price_View_Types;
use SmartPriceVisibility\Enums\SPV_Apply_For;

if (!defined('ABSPATH')) {
	die;
}

/**
 * Handles plugin settings for Smart Price Visibility.
 *
 * Responsible for registering, sanitizing, retrieving, and saving
 * plugin options. Also handles AJAX requests for saving settings.
 */
final class SPV_Settings
{
	/**
	 * Option key used to store plugin settings in the database.
	 */
	public const OPTION_KEY = 'spv_options';

	/**
	 * Initialize settings hooks.
	 *
	 * Registers settings with WordPress and adds AJAX handler for saving.
	 *
	 * @return void
	 */
	public static function init(): void
	{
		add_action('admin_init', [self::class, 'registerSettings']);

		// AJAX for saving settings
		add_action('wp_ajax_spv_save_settings', [self::class, 'ajaxSaveSettings']);
	}

	/**
	 * Register plugin settings with WordPress.
	 *
	 * Sets default values and sanitization callback.
	 *
	 * @return void
	 */
	public static function registerSettings(): void
	{
		register_setting(
			'spv_settings_group',
			self::OPTION_KEY,
			[
				'type' => 'array',
				'default' => [
					'mode' => SPV_Price_View_Types::WITHOUT_CHANGES,
					'apply_for' => SPV_Apply_For::EVERYONE,
					'hide_add_to_cart' => 0,
					'custom_text' => '',
					'custom_form_text' => '',
				],
				'sanitize_callback' => [self::class, 'sanitize'],
			]
		);
	}

	/**
	 * Sanitize plugin settings.
	 *
	 * Merges input with defaults, validates allowed values, and normalizes checkboxes.
	 *
	 * @param array $input User-submitted settings
	 * @return array Sanitized settings array
	 */
	public static function sanitize(array $input): array
	{
		$defaults = [
			'mode' => SPV_Price_View_Types::WITHOUT_CHANGES,
			'apply_for' => SPV_Apply_For::EVERYONE,
			'hide_add_to_cart' => 0,
			'custom_text' => '',
			'custom_form_text' => '',
		];

		$output = array_merge($defaults, $input);

		// Validate mode
		if (!in_array($output['mode'], SPV_Price_View_Types::ALL, true)) {
			$output['mode'] = SPV_Price_View_Types::WITHOUT_CHANGES;
		}

		// Validate apply_for
		if (!in_array($output['apply_for'], SPV_Apply_For::ALL, true)) {
			$output['apply_for'] = SPV_Apply_For::EVERYONE;
		}

		// Normalize checkbox
		$output['hide_add_to_cart'] = !empty($output['hide_add_to_cart']) ? 1 : 0;

		return $output;
	}

	/**
	 * Retrieve all plugin options.
	 *
	 * Returns stored options or defaults if not set.
	 *
	 * @return array Plugin settings
	 */
	public static function getOptions(): array
	{
		return get_option(self::OPTION_KEY, [
			'mode' => SPV_Price_View_Types::WITHOUT_CHANGES,
			'apply_for' => SPV_Apply_For::EVERYONE,
			'hide_add_to_cart' => 0,
			'custom_text' => '',
			'custom_form_text' => '',
		]);
	}

	/**
	 * AJAX handler to save plugin settings.
	 *
	 * Validates nonce, sanitizes input, updates options, and returns JSON response.
	 *
	 * @return void
	 */
	public static function ajaxSaveSettings(): void
	{
		check_ajax_referer('spv_nonce', 'nonce');

		// Get raw input, unslash, and ensure it's an array
		$raw_input = sanitize_text_field( wp_unslash($_POST['settings'] ?? []) );
		if (!is_array($raw_input)) {
			$raw_input = [];
		}

		// Sanitize each setting value
		$input = [];
		foreach ($raw_input as $key => $value) {
			if (is_array($value)) {
				$input[$key] = array_map('sanitize_text_field', $value);
			} else {
				$input[$key] = sanitize_text_field($value);
			}
		}

		// Merge with existing options
		$options = get_option(self::OPTION_KEY, []);
		$options = array_merge($options, $input);

		// Sanitize according to plugin rules
		$sanitized = self::sanitize($options);

		update_option(self::OPTION_KEY, $sanitized);

		wp_send_json_success(['message' => 'Settings saved']);
	}
}
