<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );

	return;
}

?>
<div class="box-alert-product">
    <div class="box-item-pro">
        <div class="item-product">
            <div class="item-product-con">
                <i class="fas fa-check"></i>
            </div>

        </div>
        <p class="text-pro">
			<?php echo get_the_title( MONA_WC_CHECKOUT ); ?>
        </p>
    </div>
    <span class="line-pro"></span>
    <div class="box-item-pro">
        <div class="item-product">
            <div class="item-product-con">
                2
            </div>

        </div>
        <p class="text-pro">
			<?php echo __( 'Hoàn tất đơn hàng', 'monamedia' ); ?>
        </p>
    </div>
</div>
<form name="checkout" method="post" class="checkout woocommerce-checkout"
      action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
    <div class="content-success-con paysec d-wrap">

        <div class="con-left d-item d-2">


			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div class="col2-set" id="customer_details">
                    <div class="col-1">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>

                    <div class="col-2">
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
                    </div>
                </div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                <div class="content-left">
                    <div class="title">
                        <p class="title-text mb-20">
							<?php echo __( 'Phương thức vận chuyển', 'monamedia' ); ?>
                        </p>

                        <div class="infodlvr-payment check-style recheck-block mb-30">
                            <div class="infodlvr-payment-item recheck-item">
                                <input type="radio" hidden="" class="recheck-input">
                                <span class="infodlvr-check checkbox"></span>
								<?php echo __( 'Giao hàng tận nơi', 'monamedia' ); ?>
                                <span class="price">			<?php echo WC()->cart->get_cart_shipping_total(); ?></span>
                            </div>
                        </div>

                        <p class="title-text mb-20">
                            Phương thức thanh toán
                        </p>

                        <!--                        <div class="infodlvr-payment check-style recheck-block">-->
                        <!--                            <div class="infodlvr-payment-item recheck-item">-->
                        <!--                                <input type="radio" hidden="" class="recheck-input">-->
                        <!--                                <span class="infodlvr-check checkbox"></span>-->
                        <!--                                <span class="img">-->
                        <!--                                                <img src="-->
						<?php //echo get_site_url(); ?><!--/template/assets/images/money-icon.svg"-->
                        <!--                                                     alt="">-->
                        <!--                                            </span>-->
                        <!--                                Thanh toán tiền mặt khi nhận hàng (COD)-->
                        <!--                            </div>-->
                        <!--                            <div class="infodlvr-payment-item recheck-item">-->
                        <!--                                <input type="radio" hidden="" class="recheck-input">-->
                        <!--                                <span class="infodlvr-check checkbox"></span>-->
                        <!--                                <span class="img">-->
                        <!--                                                <img src="-->
						<?php //echo get_site_url(); ?><!--/template/assets/images/atm-card-icon.svg"-->
                        <!--                                                     alt="">-->
                        <!--                                            </span>-->
                        <!--                                Chuyển khoản ngân hàng-->
                        <!--                            </div>-->
                        <!--                        </div>-->

                        <ul class="infodlvr-payment check-style recheck-block wc_payment_methods payment_methods methods">
							<?php
							$total = 0;
							if ( ! empty( WC()->cart->cart_contents_total ) ) {
								$total = WC()->cart->cart_contents_total;
							}

							$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
							if ( ! empty( $available_gateways ) ) {
								foreach ( $available_gateways as $gateway ) {
									wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
								}
							} else {
								echo '<li class="infodlvr-payment-item recheck-item woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
							}
							?>
                        </ul>
                    </div>
                </div>

			<?php endif; ?>
			<?php ?>

        </div>

        <div class="con-right d-item d-2">

			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
            <div class="title">
                <p class="title-text">
					<?php echo __( 'Thông tin đơn hàng', 'monamedia' ); ?>
                </p>
            </div>

			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>

			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
        </div>
    </div>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
