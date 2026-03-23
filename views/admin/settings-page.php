<?php

use MSToolsPriceVisibility\Enums\MSTPVFW_Price_View_Types;
use MSToolsPriceVisibility\Enums\MSTPVFW_Apply_For;
use MSToolsPriceVisibility\MSTPVFW_Settings;

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html__('MSTools Price Visibility', 'mstools-price-visibility-for-woocommerce'); ?></h1>

    <form id="mstpvfw-settings-form">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="mstpvfw_apply_for"><?php echo esc_html__('Apply for', 'mstools-price-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <select id="mstpvfw_apply_for" name="apply_for">
                        <?php // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals ?>
                        <?php foreach (MSTPVFW_Apply_For::labels() as $value => $label) : ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected(MSTPVFW_Settings::getOptions()['apply_for'], $value); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="mstpvfw_mode"><?php echo esc_html__('Mode', 'mstools-price-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <select id="mstpvfw_mode" name="mode">
                        <?php // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals ?>
                        <?php foreach (MSTPVFW_Price_View_Types::labels() as $value => $label) : ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected(MSTPVFW_Settings::getOptions()['mode'], $value); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr id="mstpvfw_custom_text_row" style="display:none;">
                <th scope="row">
                    <label for="mstpvfw_custom_text"><?php echo esc_html__('Custom Text', 'mstools-price-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <input type="text" id="mstpvfw_custom_text" name="custom_text" value="<?php echo esc_attr(MSTPVFW_Settings::getOptions()['custom_text'] ?? ''); ?>" class="regular-text">
                    <span class="description"><?php echo esc_html__('Text displayed instead of price when the mode is set to show text.', 'mstools-price-visibility-for-woocommerce'); ?></span>
                </td>
            </tr>

            <tr id="mstpvfw_custom_form_text_row" style="display:none;">
                <th scope="row">
                    <label for="mstpvfw_custom_form_text"><?php echo esc_html__('Custom Form Text', 'mstools-price-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <input type="text" id="mstpvfw_custom_form_text" name="custom_form_text" value="<?php echo esc_attr(MSTPVFW_Settings::getOptions()['custom_form_text'] ?? ''); ?>" class="regular-text">
                    <span class="description"><?php echo esc_html__('Text displayed above the form when the mode is set to show request form.', 'mstools-price-visibility-for-woocommerce'); ?></span>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="mstpvfw_hide_add_to_cart"><?php echo esc_html__('Hide Add to Cart button', 'mstools-price-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <input type="checkbox" id="mstpvfw_hide_add_to_cart" name="hide_add_to_cart" value="1" <?php checked(MSTPVFW_Settings::getOptions()['hide_add_to_cart'], 1); ?>>
                    <span class="description"><?php echo esc_html__('Hide the Add to Cart button on the shop and product pages.', 'mstools-price-visibility-for-woocommerce'); ?></span>
                </td>
            </tr>

        </table>

        <p>
            <button type="button" class="button button-primary" id="mstpvfw-save-btn">
                <?php echo esc_html__('Save Settings', 'mstools-price-visibility-for-woocommerce'); ?>
            </button>
        </p>
    </form>
</div>