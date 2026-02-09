<?php

namespace NeonWebId\WooCouponHistory;

use WC_Coupon;
use WC_Product;
use WP_Post;

/**
 * Class Admin
 *
 * Menangani tampilan dan fungsionalitas di area admin WooCommerce.
 */
class Admin
{
    /**
     * Menambahkan tab kustom ke panel data kupon.
     *
     * @param  array<string, array>  $tabs
     * @return array<string, array>
     */
    public function addCouponHistoryTab(array $tabs): array
    {
        $tabs['coupon_history'] = [
            'label'  => __('Coupon History', 'woocouponhistory'),
            'target' => 'coupon_history_data',
            'class'  => ['coupon_history_tab'],
        ];

        return $tabs;
    }

    /**
     * Menampilkan panel input di dalam tab kustom.
     */
    public function renderCouponHistoryPanel(): void
    {
        global $post;

        if (!$post instanceof WP_Post) {
            return;
        }

        $coupon        = new WC_Coupon($post->ID);
        $productIdsRaw = $coupon->get_meta('wch_required_past_product_ids');
        $productIds    = !empty($productIdsRaw) ? explode(',', (string) $productIdsRaw) : [];

        ?>
        <div id="coupon_history_data" class="panel woocommerce_options_panel">
            <div class="options_group">
                <p class="form-field">
                    <label for="wch_required_past_product_ids">
                        <?php esc_html_e('Required Past Purchase', 'woocouponhistory'); ?>
                    </label>
                    <select class="wc-product-search"
                            multiple="multiple"
                            style="width: 50%;"
                            id="wch_required_past_product_ids"
                            name="wch_required_past_product_ids[]"
                            data-placeholder="<?php esc_attr_e('Search for a product...', 'woocouponhistory'); ?>"
                            data-action="woocommerce_json_search_products_and_variations">
                        <?php
                        foreach ($productIds as $productId) {
                            $product = wc_get_product((int) $productId);
                            if ($product instanceof WC_Product) {
                                printf(
                                    '<option value="%s" selected="selected">%s</option>',
                                    esc_attr($productId),
                                    wp_kses_post($product->get_formatted_name())
                                );
                            }
                        }
                        ?>
                    </select>
                    <?php echo wc_help_tip(__('The customer must have purchased at least one of these products in the past to use this coupon.',
                        'woocouponhistory')); ?>
                </p>
            </div>
        </div>
        <?php
    }

    /**
     * Menyimpan data field kustom.
     *
     * @param  int  $postId
     * @param  WC_Coupon  $coupon
     */
    public function saveCouponMeta(int $postId, WC_Coupon $coupon): void
    {
        $postedData = $_POST['wch_required_past_product_ids'] ?? null;

        if (is_array($postedData)) {
            $productIds = array_map('absint', $postedData);
            $coupon->update_meta_data('wch_required_past_product_ids', implode(',', $productIds));
        } else {
            $coupon->update_meta_data('wch_required_past_product_ids', '');
        }

        $coupon->save();
    }

    /**
     * Menambahkan CSS ikon tab.
     */
    public function addAdminStyles(): void
    {
        ?>
        <style>
            #woocommerce-coupon-data ul.wc-tabs li.coupon_history_tab a::before {
                content: "\f203";
            }
        </style>
        <?php
    }
}