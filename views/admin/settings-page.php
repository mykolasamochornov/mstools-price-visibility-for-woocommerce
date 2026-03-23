<?php

use PricePilotVisibility\Enums\PPVFW_Price_View_Types;
use PricePilotVisibility\Enums\PPVFW_Apply_For;
use PricePilotVisibility\PPVFW_Settings;

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html__('PricePilot Visibility', 'price-pilot-visibility-for-woocommerce'); ?></h1>

    <form id="ppvfw-settings-form">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="ppvfw_apply_for"><?php echo esc_html__('Apply for', 'price-pilot-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <select id="ppvfw_apply_for" name="apply_for">
                        <?php // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals ?>
                        <?php foreach (PPVFW_Apply_For::labels() as $value => $label) : ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected(PPVFW_Settings::getOptions()['apply_for'], $value); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="ppvfw_mode"><?php echo esc_html__('Mode', 'price-pilot-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <select id="ppvfw_mode" name="mode">
                        <?php // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals ?>
                        <?php foreach (PPVFW_Price_View_Types::labels() as $value => $label) : ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected(PPVFW_Settings::getOptions()['mode'], $value); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr id="ppvfw_custom_text_row" style="display:none;">
                <th scope="row">
                    <label for="ppvfw_custom_text"><?php echo esc_html__('Custom Text', 'price-pilot-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <input type="text" id="ppvfw_custom_text" name="custom_text" value="<?php echo esc_attr(PPVFW_Settings::getOptions()['custom_text'] ?? ''); ?>" class="regular-text">
                    <span class="description"><?php echo esc_html__('Text displayed instead of price when the mode is set to show text.', 'price-pilot-visibility-for-woocommerce'); ?></span>
                </td>
            </tr>

            <tr id="ppvfw_custom_form_text_row" style="display:none;">
                <th scope="row">
                    <label for="ppvfw_custom_form_text"><?php echo esc_html__('Custom Form Text', 'price-pilot-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <input type="text" id="ppvfw_custom_form_text" name="custom_form_text" value="<?php echo esc_attr(PPVFW_Settings::getOptions()['custom_form_text'] ?? ''); ?>" class="regular-text">
                    <span class="description"><?php echo esc_html__('Text displayed above the form when the mode is set to show request form.', 'price-pilot-visibility-for-woocommerce'); ?></span>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="ppvfw_hide_add_to_cart"><?php echo esc_html__('Hide Add to Cart button', 'price-pilot-visibility-for-woocommerce'); ?></label>
                </th>
                <td>
                    <input type="checkbox" id="ppvfw_hide_add_to_cart" name="hide_add_to_cart" value="1" <?php checked(PPVFW_Settings::getOptions()['hide_add_to_cart'], 1); ?>>
                    <span class="description"><?php echo esc_html__('Hide the Add to Cart button on the shop and product pages.', 'price-pilot-visibility-for-woocommerce'); ?></span>
                </td>
            </tr>

        </table>

        <p>
            <button type="button" class="button button-primary" id="ppvfw-save-btn">
                <?php echo esc_html__('Save Settings', 'price-pilot-visibility-for-woocommerce'); ?>
            </button>
        </p>
    </form>
</div>

<script type="text/javascript">
(function($){
    function toggleCustomRows() {
        var mode = $('#ppvfw_mode').val();

        if (mode === '<?php echo esc_js(PPVFW_Price_View_Types::HIDE_PRICE_AND_SHOW_TEXT); ?>') {
            $('#ppvfw_custom_text_row').show();
        } else {
            $('#ppvfw_custom_text_row').hide();
        }

        if (mode === '<?php echo esc_js(PPVFW_Price_View_Types::HIDE_PRICE_AND_SHOW_FORM_REQUEST); ?>') {
            $('#ppvfw_custom_form_text_row').show();
        } else {
            $('#ppvfw_custom_form_text_row').hide();
        }
    }

    $(document).ready(function(){
        toggleCustomRows();
        $('#ppvfw_mode').on('change', toggleCustomRows);
    });
})(jQuery);
</script>