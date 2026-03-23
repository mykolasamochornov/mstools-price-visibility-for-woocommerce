=== PricePilot Visibility for WooCommerce ===
Contributors: msamochornov
Tags: woocommerce, price, visibility, hide, request
Requires at least: 5.9
Tested up to: 6.9
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: price-pilot-visibility-for-woocommerce
Requires Plugins: woocommerce

Hide WooCommerce prices or replace them with text or a request form.

== Description ==

Plugin URI: https://github.com/mykolasamochornov/price-pilot-visibility-for-woocommerce
Author: Mykola Samochornov
Author URI: https://github.com/mykolasamochornov
Version: 1.0.0
License: GPL2+

PricePilot Visibility allows you to control price visibility in WooCommerce:

- Hide product prices for guests or all users
- Replace prices with custom text
- Show a request form instead of the price
- Optionally hide the "Add to Cart" button

== Features ==

- Select how prices are displayed:
  - Without changes
  - Hide price
  - Hide price and show text
  - Hide price and show request form
- Apply rules for everyone or guests only
- Custom text replacement for hidden prices
- Custom text for the price request form
- Automatically create orders with the Price Request status

== Installation ==

1. Copy the plugin folder to wp-content/plugins/
2. Activate the plugin via the Plugins menu in WordPress
3. Go to WooCommerce → PricePilot Visibility to configure settings

== Settings ==

- Mode: choose how prices are displayed
- Apply for: select who the rules apply to
- Hide Add to Cart button: enable or disable
- Custom text: text to replace hidden prices
- Request form text: text above the price request form

== Usage ==

- The plugin automatically hides prices and/or the Add to Cart button on single product and archive pages
- The price request form sends an AJAX request and creates an order with Price Request status

== Development / Git Workflow ==

This plugin uses a simple Git workflow with main and dev branches.

=== Local Setup ===

1. Clone the repository

git clone https://github.com/mykolasamochornov/price-pilot-visibility-for-woocommerce.git

2. Go to the plugin folder

cd price-pilot-visibility-for-woocommerce