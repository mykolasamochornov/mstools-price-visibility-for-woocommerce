<?php

declare(strict_types=1);

namespace MSToolsPriceVisibility;

use MSToolsPriceVisibility\MSTPVFW_Settings;
use MSToolsPriceVisibility\Enums\MSTPVFW_Price_View_Types;
use MSToolsPriceVisibility\Enums\MSTPVFW_Apply_For;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles product price visibility logic for the MSTools Price Visibility plugin.
 *
 * Responsible for filtering product prices, hiding add-to-cart buttons,
 * and rendering a request price form based on plugin settings and user type.
 */
class MSTPVFW_Price_Handler
{
    /**
     * Plugin options retrieved from MSTPVFW_Settings.
     *
     * @var array
     */
    private $options = [];

    /**
     * Initialize the price handler.
     *
     * Sets options, registers filters for price HTML, add-to-cart buttons,
     * and renders the request form where applicable.
     *
     * @return void
     */
    public function init(): void
    {
        $this->options = MSTPVFW_Settings::getOptions();

        // Filter product price HTML
        add_filter('woocommerce_get_price_html', [$this, 'filterPrice'], 9999, 2);

        // Hide Add to Cart buttons if option enabled
        if (!empty($this->options['hide_add_to_cart'])) {
            $this->applyHideAddToCartFilters();
        }

        // Render the request form via hooks
        add_action('woocommerce_after_shop_loop_item', [$this, 'renderRequestFormHook'], 20);
        add_action('woocommerce_single_product_summary', [$this, 'renderRequestFormHook'], 60);
    }

    /**
     * Filter the product price HTML based on plugin settings and user type.
     *
     * @param string $price Original product price HTML
     * @param mixed $product WooCommerce product object
     * @return string Modified price HTML
     */
    public function filterPrice(string $price, $product): string
    {
        $mode = $this->options['mode'] ?? MSTPVFW_Price_View_Types::WITHOUT_CHANGES;
        $apply_for = $this->options['apply_for'] ?? MSTPVFW_Apply_For::EVERYONE;

        if ($mode === MSTPVFW_Price_View_Types::WITHOUT_CHANGES) {
            return $price;
        }

        $apply = false;
        switch ($apply_for) {
            case MSTPVFW_Apply_For::EVERYONE:
                $apply = true;
                break;
            case MSTPVFW_Apply_For::GUESTS_ONLY:
                $apply = !is_user_logged_in();
                break;
        }

        if (!$apply) {
            return $price;
        }

        switch ($mode) {
            case MSTPVFW_Price_View_Types::HIDE_PRICE:
                return '';
            case MSTPVFW_Price_View_Types::HIDE_PRICE_AND_SHOW_TEXT:
                $text = $this->options['custom_text'] ?? '';
                return '<span class="mstpvfw-hidden-price">' . esc_html($text) . '</span>';
            case MSTPVFW_Price_View_Types::HIDE_PRICE_AND_SHOW_FORM_REQUEST:
                return ''; // Form rendered via hook
            default:
                return $price;
        }
    }

    /**
     * Apply filters to hide Add to Cart buttons based on plugin settings.
     *
     * Hides buttons on single product and loop/archive pages depending on user type.
     *
     * @return void
     */
    private function applyHideAddToCartFilters(): void
    {
        $apply_for = $this->options['apply_for'] ?? MSTPVFW_Apply_For::EVERYONE;

        // Single product
        add_filter('woocommerce_is_purchasable', function($purchasable, $product) use ($apply_for) {
            $apply = false;
            switch ($apply_for) {
                case MSTPVFW_Apply_For::EVERYONE:
                    $apply = true;
                    break;
                case MSTPVFW_Apply_For::GUESTS_ONLY:
                    $apply = !is_user_logged_in();
                    break;
            }
            return $apply ? false : $purchasable;
        }, 9999, 2);

        // Loop / archive pages
        add_filter('woocommerce_loop_add_to_cart_link', function($button) use ($apply_for) {
            $apply = false;
            switch ($apply_for) {
                case MSTPVFW_Apply_For::EVERYONE:
                    $apply = true;
                    break;
                case MSTPVFW_Apply_For::GUESTS_ONLY:
                    $apply = !is_user_logged_in();
                    break;
            }
            return $apply ? '' : $button;
        }, 9999);

        // Variable product
        add_action('woocommerce_variable_add_to_cart', function() use ($apply_for) {
            $apply = false;
            switch ($apply_for) {
                case MSTPVFW_Apply_For::EVERYONE:
                    $apply = true;
                    break;
                case MSTPVFW_Apply_For::GUESTS_ONLY:
                    $apply = !is_user_logged_in();
                    break;
            }
            if ($apply) {
                remove_action(
                    'woocommerce_single_variation',
                    'woocommerce_single_variation_add_to_cart_button',
                    20
                );
            }
        }, 1);
    }

    /**
     * Render the request form on WooCommerce hooks if the mode requires it.
     *
     * @return void
     */
    public function renderRequestFormHook(): void
    {
        $mode = $this->options['mode'] ?? MSTPVFW_Price_View_Types::WITHOUT_CHANGES;
        $apply_for = $this->options['apply_for'] ?? MSTPVFW_Apply_For::EVERYONE;

        if ($mode !== MSTPVFW_Price_View_Types::HIDE_PRICE_AND_SHOW_FORM_REQUEST) {
            return;
        }

        $apply = false;
        switch ($apply_for) {
            case MSTPVFW_Apply_For::EVERYONE:
                $apply = true;
                break;
            case MSTPVFW_Apply_For::GUESTS_ONLY:
                $apply = !is_user_logged_in();
                break;
        }

        if (!$apply) return;

        global $product;
        echo wp_kses(
            $this->renderRequestForm($product),
            [
                'div' => ['class' => []],
                'form' => ['class' => [], 'data-product-id' => []],
                'p' => ['class' => []],
                'input' => ['type' => [], 'name' => [], 'class' => [], 'placeholder' => [], 'required' => []],
                'button' => ['type' => [], 'class' => []],
            ]
        );
    }

    /**
     * Render the HTML for the request price form.
     *
     * @param mixed $product WooCommerce product object
     * @return string HTML output for the form
     */
    private function renderRequestForm($product): string
    {
        $product_id = $product->get_id();
        $form_text = $this->options['custom_form_text'] ?? '';

        ob_start();
        ?>
        <div class="mstpvfw-request-wrapper woocommerce">
            <?php if (!empty($form_text)) : ?>
                <p class="mstpvfw-form-text"><?php echo esc_html($form_text); ?></p>
            <?php endif; ?>
            <form class="mstpvfw-request-form" data-product-id="<?php echo esc_attr($product_id); ?>">
                <p class="form-row form-row-wide">
                    <input type="email" name="mstpvfw_email" class="mstpvfw-email input-text" placeholder="<?php echo esc_attr__('Your email', 'mstools-price-visibility-for-woocommerce'); ?>" required>
                </p>
                <p class="form-row">
                    <button type="submit" class="mstpvfw-button button wp-element-button wc-block-components-product-button__button"><?php echo esc_html__('Request Price', 'mstools-price-visibility-for-woocommerce'); ?></button>
                </p>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
}
