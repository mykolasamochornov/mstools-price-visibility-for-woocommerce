# Smart Price Visibility for WooCommerce

**Plugin URI:** https://github.com/mykolasamochornov/smart-price-visibility-for-woocommerce  
**Author:** Mykola Samochornov  
**Author URI:** https://github.com/mykolasamochornov  
**Version:** 1.0.0  
**License:** GPL2+  
**Text Domain:** smart-price-visibility  
**Requires Plugins:** WooCommerce  

---

## Description

Smart Price Visibility allows you to control price visibility in WooCommerce:  
- Hide product prices for guests or all users.  
- Replace prices with custom text.  
- Show a request form instead of the price.  
- Optionally hide the “Add to Cart” button.  

---

## Features

- Select how prices are displayed:
  - Without changes
  - Hide price
  - Hide price and show text
  - Hide price and show request form
- Apply rules for everyone or guests only
- Custom text replacement for hidden prices
- Custom text for the price request form
- Automatically create orders with the **Price Request** status

---

## Installation

1. Copy the plugin folder to `wp-content/plugins/`  
2. Activate the plugin via the **Plugins** menu in WordPress  
3. Go to **WooCommerce → Price Visibility** to configure settings  

---

## Settings

- **Mode:** choose how prices are displayed  
- **Apply for:** select who the rules apply to  
- **Hide Add to Cart button:** enable or disable  
- **Custom text:** text to replace hidden prices  
- **Request form text:** text above the price request form  

---

## Usage

- The plugin automatically hides prices and/or the Add to Cart button on single product and archive pages  
- The price request form sends an AJAX request and creates an order with **Price Request** status  

---

## Development / Git Workflow

This plugin uses a simple Git workflow with `main` and `dev` branches:

### Local Setup

1. Clone the repository:

```bash
git clone https://github.com/mykolasamochornov/smart-price-visibility-for-woocommerce.git
cd smart-price-visibility-for-woocommerce