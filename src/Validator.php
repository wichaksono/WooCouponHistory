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

        $requiredIds = array_filter(array_map('trim', explode(',', $requiredIdsString)));

        if (empty($requiredIds)) {
            return $valid;
        }

        if (!is_user_logged_in()) {
            throw new Exception(__('You must be logged in to use this loyalty coupon.', 'woocouponhistory'));
        }

        $currentUser = wp_get_current_user();
        $userEmail   = $currentUser->user_email;
        $userId      = $currentUser->ID;
        $hasBought   = false;

        foreach ($requiredIds as $productId) {
            if (wc_customer_bought_product($userEmail, $userId, (int) $productId)) {
                $hasBought = true;
                break;
            }
        }

        if (!$hasBought) {
            throw new Exception(__('Sorry, this coupon is exclusively for customers who have purchased specific products previously.',
                'woocouponhistory'));
        }

        return $valid;
    }
}