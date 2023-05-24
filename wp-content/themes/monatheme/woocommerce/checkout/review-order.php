<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<table class="shop_table woocommerce-checkout-review-order-table">
    <tbody>
	<?php
	do_action( 'woocommerce_review_order_before_cart_contents' );

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

			// Check if product is on sale
			$is_on_sale = $_product->is_on_sale();

			$attachments = isset( $cart_item['attachments'] ) ? $cart_item['attachments'] : [];
			$cart_note   = isset( $cart_item['note'] ) ? esc_attr( $cart_item['note'] ) : '';
			?>
            <tr class="item-product <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                <td class="item-img product-image">
					<?php
					if ( $_product && has_post_thumbnail( $_product->get_id() ) ) {
						echo apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					} else { ?>
                        <img src="<?php echo get_template_directory_uri() ?>/public/helpers/images/default-thumbnail.jpg"
                             alt="">
						<?php
					} ?>
                </td>
                <td class="product-name">
                    <div class="item-content">
                        <p class="title-con">
							<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
                        </p>
						<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . __( 'Số Lượng', 'monamedia' ) . sprintf( ': %s', $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                </td>
                <td class="product-total">
                    <div class="price">
						<?php if ( $is_on_sale ): ?>
                            <p class="price-text">
								<?php echo number_format( $_product->get_sale_price(), 0, '.', ',' ) . __( 'đ', 'monamedia' ); ?>
                            </p>
                            <div class="price-line">
								<?php echo number_format( $_product->get_regular_price(), 0, '.', ',' ) . __( 'đ', 'monamedia' ); ?>
                            </div>
						<?php else: ?>
                            <p class="price-text">
								<?php echo number_format( $_product->get_regular_price(), 0, '.', ',' ) . __( 'đ', 'monamedia' ); ?>
                            </p>
                            <div class="price-line">
                                <!-- leave empty if not on sale -->
                            </div>
						<?php endif; ?>
                    </div>
                </td>
            </tr>
            <tr class="box-list-image">
                <td colspan="3">
                    <div class="preview-media is-loading-group">
						<?php echo get_review_media( $attachments, $cart_item_key ); ?>
                    </div>
					<?php if ( ! empty ( $cart_note ) ) { ?>
                        <div class="note-product mb-24">
                            <p class="text-note"><?php echo $cart_note ?></p>
                        </div>
					<?php } ?>
                </td>
            </tr>
			<?php
		} ?>
		<?php
	}

	do_action( 'woocommerce_review_order_after_cart_contents' );
	?>
    </tbody>


    <tfoot class="billdlvr-bill">

    <tr>
        <th class="text"><?php echo __( 'Tổng sản phẩm', 'monamedia' ); ?>:</th>
        <td colspan="2" class="info"><?php echo WC()->cart->get_cart_contents_count(); ?></td>
    </tr>
    <tr class="cart-subtotal">
        <th><?php echo __( 'Tạm tính', 'monamedia' ); ?></th>
        <td colspan="2"><?php wc_cart_totals_subtotal_html(); ?></td>
    </tr>

    <tr>
        <th><span class="text"><?php echo __( 'Tổng giảm giá', 'monamedia' ); ?>:</span></th>
        <td colspan="2"><span class="info"><?php echo WC()->cart->get_cart_discount_total(); ?></span></td>
    </tr>
    <tr>
        <th><span class="text"><?php echo __( 'Mã khuyến mãi', 'monamedia' ); ?>:</span></th>
        <td colspan="2"><span
                    class="info"><?php echo WC()->cart->get_applied_coupons() ? implode( ', ', WC()->cart->get_applied_coupons() ) : '-'; ?></span>
        </td>
    </tr>
    <tr>
        <th><span class="text"><?php echo __( 'Phí vận chuyển', 'monamedia' ); ?>:</span></th>
        <td colspan="2">
			<?php echo WC()->cart->get_cart_shipping_total(); ?>
        </td>
    </tr>


	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
        <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
            <th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
            <td colspan="2"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
        </tr>
	<?php endforeach; ?>


	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

		<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

		<?php wc_cart_totals_shipping_html(); ?>

		<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

	<?php endif; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
        <tr class="fee">
            <th><?php echo esc_html( $fee->name ); ?></th>
            <td colspan="2"><?php wc_cart_totals_fee_html( $fee ); ?></td>
        </tr>
	<?php endforeach; ?>
	<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
		<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                    <th><?php echo esc_html( $tax->label ); ?></th>
                    <td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                </tr>
			<?php endforeach; ?>
		<?php else : ?>
            <tr class="tax-total">
                <th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
                <td><?php wc_cart_totals_taxes_total_html(); ?></td>
            </tr>
		<?php endif; ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
    <tr class="order-total billdlvr-bill bottom">
        <th><?php echo __( 'Tạm tính', 'monamedia' ); ?></th>
        <td colspan="2"><?php wc_cart_totals_order_total_html(); ?></td>
    </tr>

	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

    </tfoot>
</table>


