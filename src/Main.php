<?php

namespace NeonWebId\WooCouponHistory;

/**
 * Class Main
 *
 * Kelas utama untuk mengorkestrasi inisialisasi plugin.
 */
class Main
{
    /**
     * Inisialisasi komponen-komponen plugin.
     */
    public static function init(): void
    {
        $admin = new Admin();
        $validator = new Validator();

        // Hook Admin
        add_filter('woocommerce_coupon_data_tabs', [$admin, 'addCouponHistoryTab']);
        add_action('woocommerce_coupon_data_panels', [$admin, 'renderCouponHistoryPanel']);
        add_action('woocommerce_coupon_options_save', [$admin, 'saveCouponMeta'], 10, 2);
        add_action('admin_head', [$admin, 'addAdminStyles']);

        // Hook Frontend/Validation
        add_filter('woocommerce_coupon_is_valid', [$validator, 'validatePurchaseHistory'], 10, 2);
    }
}
