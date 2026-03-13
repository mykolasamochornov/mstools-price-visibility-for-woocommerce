<?php

use SmartPriceVisibility\Enums\SPV_Price_View_Types;
use SmartPriceVisibility\Enums\SPV_Apply_For;
use SmartPriceVisibility\SPV_Settings;

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html__('Smart Price Visibility', 'smart-price-visibility-for-woocommerce'); ?></h1>

    <form id="spv-settings-form">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="spv_apply_for"><?php echo esc_html__('Apply for', 'smart-price-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <select id="spv_apply_for" name="apply_for">
                        <?php // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals ?>
                        <?php foreach (SPV_Apply_For::labels() as $value => $label) : ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected(SPV_Settings::getOptions()['apply_for'], $value); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="spv_mode"><?php echo esc_html__('Mode', 'smart-price-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <select id="spv_mode" name="mode">
                        <?php // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals ?>
                        <?php foreach (SPV_Price_View_Types::labels() as $value => $label) : ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected(SPV_Settings::getOptions()['mode'], $value); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr id="spv_custom_text_row" style="display:none;">
                <th scope="row">
                    <label for="spv_custom_text"><?php echo esc_html__('Custom Text', 'smart-price-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <input type="text" id="spv_custom_text" name="custom_text" value="<?php echo esc_attr(SPV_Settings::getOptions()['custom_text'] ?? ''); ?>" class="regular-text">
                    <span class="description"><?php echo esc_html__('Text displayed instead of price when the mode is set to show text.', 'smart-price-visibility-for-woocommerce'); ?></span>
                </td>
            </tr>

            <tr id="spv_custom_form_text_row" style="display:none;">
                <th scope="row">
                    <label for="spv_custom_form_text"><?php echo esc_html__('Custom Form Text', 'smart-price-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <input type="text" id="spv_custom_form_text" name="custom_form_text" value="<?php echo esc_attr(SPV_Settings::getOptions()['custom_form_text'] ?? ''); ?>" class="regular-text">
                    <span class="description"><?php echo esc_html__('Text displayed above the form when the mode is set to show request form.', 'smart-price-visibility-for-woocommerce'); ?></span>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="spv_hide_add_to_cart"><?php echo esc_html__('Hide Add to Cart button', 'smart-price-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <input type="checkbox" id="spv_hide_add_to_cart" name="hide_add_to_cart" value="1" <?php checked(SPV_Settings::getOptions()['hide_add_to_cart'], 1); ?>>
                    <span class="description"><?php echo esc_html__('Hide the Add to Cart button on the shop and product pages.', 'smart-price-visibility-for-woocommerce'); ?></span>
                </td>
            </tr>

        </table>

        <p>
            <button type="button" class="button button-primary" id="spv-save-btn">
                <?php echo esc_html__('Save Settings', 'smart-price-visibility-for-woocommerce'); ?>
            </button>
        </p>
    </form>
</div>

<script type="text/javascript">
(function($){
    function toggleCustomRows() {
        var mode = $('#spv_mode').val();

        if (mode === '<?php echo esc_js(SPV_Price_View_Types::HIDE_PRICE_AND_SHOW_TEXT); ?>') {
            $('#spv_custom_text_row').show();
        } else {
            $('#spv_custom_text_row').hide();
        }

        if (mode === '<?php echo esc_js(SPV_Price_View_Types::HIDE_PRICE_AND_SHOW_FORM_REQUEST); ?>') {
            $('#spv_custom_form_text_row').show();
        } else {
            $('#spv_custom_form_text_row').hide();
        }
    }

    $(document).ready(function(){
        toggleCustomRows();
        $('#spv_mode').on('change', toggleCustomRows);
    });
})(jQuery);
</script>