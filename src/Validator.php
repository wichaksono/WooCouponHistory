<?php

namespace NeonWebId\WooCouponHistory;

use Exception;
use WC_Coupon;
use WP_User;

/**
 * Class Validator
 *
 * Menangani validasi kupon berdasarkan riwayat pembelian.
 */
class Validator
{
    /**
     * Memvalidasi apakah kupon layak digunakan oleh pelanggan.
     *
     * @param  bool  $valid
     * @param  WC_Coupon  $coupon
     * @return bool
     * @throws Exception
     */
    public function validatePurchaseHistory(bool $valid, WC_Coupon $coupon): bool
    {
        $requiredIdsString = (string) $coupon->get_meta('wch_required_past_product_ids');

        if (empty($requiredIdsString)) {
            return $valid;
        }

        $requiredIds = array_filter(array_map('absint', explode(',', $requiredIdsString)));

        if (empty($requiredIds)) {
            return $valid;
        }

        if (!is_user_logged_in()) {
            throw new Exception(__('You must be logged in to use this loyalty coupon.', 'woocouponhistory'));
        }

        $currentUser = wp_get_current_user();

        // Menggunakan metode yang lebih modern dan kompatibel dengan HPOS
        if (!$this->hasCustomerPurchasedProducts($currentUser, $requiredIds)) {
            throw new Exception(__('Sorry, this coupon is exclusively for customers who have purchased specific products previously.',
                'woocouponhistory'));
        }

        return $valid;
    }

    /**
     * Mengecek riwayat pembelian menggunakan WC_Order_Query (Aman untuk HPOS & Multiple Items).
     *
     * @param  WP_User  $user
     * @param  array<int>  $productIds
     * @return bool
     */
    private function hasCustomerPurchasedProducts(WP_User $user, array $productIds): bool
    {
        // Cari pesanan pelanggan dengan status sukses
        $orders = wc_get_orders([
            'customer' => [$user->ID, $user->user_email],
            'status'   => ['wc-completed', 'wc-processing'],
            'limit'    => -1, // Ambil semua riwayat
            'return'   => 'ids',
        ]);

        if (empty($orders)) {
            return false;
        }

        global $wpdb;
        $orderIdsIn = implode(',', array_map('absint', $orders));
        $productsIn = implode(',', array_map('absint', $productIds));

        // Cari item di dalam pesanan-pesanan tersebut yang cocok dengan ID produk syarat
        $count = $wpdb->get_var("
            SELECT COUNT(order_item_id)
            FROM {$wpdb->prefix}woocommerce_order_itemmeta
            WHERE order_item_id IN (
                SELECT order_item_id 
                FROM {$wpdb->prefix}woocommerce_order_items 
                WHERE order_id IN ($orderIdsIn)
            )
            AND meta_key IN ('_product_id', '_variation_id')
            AND meta_value IN ($productsIn)
        ");

        return (int) $count > 0;
    }
}
