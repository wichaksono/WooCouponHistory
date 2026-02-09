<?php
/**
 * WooCouponHistory Plugin File
 *
 * @package           NeonWebId\WooCouponHistory
 * @author            NEON WEB ID
 * @copyright         2026 NEON WEB ID
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: WooCouponHistory
 * Description: Adds exclusive WooCommerce coupons for customers who have previously purchased specific products.
 * Version: 1.0.0
 * Requires at least: 6.8
 * Requires PHP: 8.2
 * Requires Plugins: woocommerce
 * Text Domain: woocouponhistory
 * Author: NEON WEB ID
 * Author URI: https://neon.web.id
 * License: GPLv3 or later
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/src/autoload.php';

add_action('plugins_loaded', function () {
    if (class_exists('WooCommerce')) {
        NeonWebId\WooCouponHistory\Main::init();
    }
});