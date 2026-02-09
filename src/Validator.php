<?php

namespace NeonWebId\WooCouponHistory;

use Exception;
use WC_Coupon;

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
     * @param bool $valid
     * @param WC_Coupon $coupon
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
        $userId      = $currentUser->ID;

        // Menggunakan SQL query langsung untuk memastikan deteksi produk dalam order jamak (multiple items)
        if (!$this->hasCustomerPurchasedProducts($userId, $requiredIds)) {
            throw new Exception(__('Sorry, this coupon is exclusively for customers who have purchased specific products previously.', 'woocouponhistory'));
        }

        return $valid;
    }

    /**
     * Mengecek riwayat pembelian menggunakan database query untuk hasil yang lebih akurat pada order jamak.
     *
     * @param int $userId
     * @param array<int> $productIds
     * @return bool
     */
    private function hasCustomerPurchasedProducts(int $userId, array $productIds): bool
    {
        global $wpdb;

        // Status pesanan yang dianggap sebagai 'sudah beli'
        $validStatuses = ['wc-completed', 'wc-processing'];
        $statusIn      = "'" . implode("','", $validStatuses) . "'";
        $productsIn    = implode(',', $productIds);

        /**
         * Query ini memeriksa tabel order_itemmeta untuk mencari ID produk di dalam pesanan milik user terkait.
         * Ini lebih robust daripada wc_customer_bought_product untuk kasus order dengan banyak produk.
         */
        $query = $wpdb->prepare(
            "SELECT COUNT(items.order_item_id) 
             FROM {$wpdb->prefix}woocommerce_order_items as items
             INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta as itemmeta ON items.order_item_id = itemmeta.order_item_id
             INNER JOIN {$wpdb->posts} as posts ON items.order_id = posts.ID
             INNER JOIN {$wpdb->postmeta} as postmeta ON posts.ID = postmeta.post_id
             WHERE posts.post_type = 'shop_order'
             AND posts.post_status IN ($statusIn)
             AND postmeta.meta_key = '_customer_user'
             AND postmeta.meta_value = %d
             AND (
                (itemmeta.meta_key = '_product_id' AND itemmeta.meta_value IN ($productsIn))
                OR 
                (itemmeta.meta_key = '_variation_id' AND itemmeta.meta_value IN ($productsIn))
             )",
            $userId
        );

        $count = $wpdb->get_var($query);

        return (int) $count > 0;
    }
}
