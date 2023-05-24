<?php
/**
 * The template for displaying index.
 *
 * @package Monamedia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * define acf
 */
if ( get_current_user_id() == 1 ) {
	define( 'ACF_LITE', false );
} else {
	define( 'ACF_LITE', true );
}

/**
 * define theme page
 */
define( 'MONA_PAGE_HOME', get_option( 'page_on_front', true ) );
define( 'MONA_PAGE_BLOG', get_option( 'page_for_posts', true ) );
define( 'MONA_PAGE_WISHLIST', url_to_postid( get_the_permalink( 185 ) ) );

require_once( get_template_directory() . '/__autoload.php' );

add_filter( 'woocommerce_billing_fields', 'mona_custom_billing_fields' );
function mona_custom_billing_fields( $fields = [] ) {
	unset( $fields['billing_first_name'] );
	unset( $fields['billing_last_name'] );
	unset( $fields['billing_phone'] );
	unset( $fields['billing_email'] );
	unset( $fields['billing_country'] );
	unset( $fields['billing_postcode'] );

	unset( $fields['billing_email']['label'] );
	unset( $fields['billing_last_name']['label'] );
	unset( $fields['billing_phone']['label'] );
	unset( $fields['billing_city']['label'] );
	unset( $fields['billing_country']['label'] );
	unset( $fields['billing_state']['label'] );

	$priority = 1;
	// $fields['billing_first_name']['priority'] = $priority++;
	$fields['billing_address_1']['priority'] = $priority ++;
	$fields['billing_state']['priority']     = $priority ++;
	$fields['billing_city']['priority']      = $priority ++;
//
	$fields['billing_first_name']['label'] = __( 'Họ và tên', 'monamedia' );
	$fields['billing_email']['label']      = __( 'Email', 'monamedia' );
	$fields['billing_phone']['label']      = __( 'Số điện thoại', 'monamedia' );
	$fields['billing_address_1']['label']  = __( 'Phường xã', 'monamedia' );
	$fields['billing_city']['label']       = __( 'Quận / Huyện', 'monamedia' );
	$fields['billing_state']['label']      = __( 'Tỉnh / Thành phố', 'monamedia' );
	$fields['billing_country']['label']    = __( 'Xã / Phường', 'monamedia' );

	/**Placeholder */
	$fields['billing_first_name']['placeholder'] = __( 'Họ và tên', 'monamedia' );
	$fields['billing_first_name']['required']    = true;
	$fields['billing_phone']['required']         = true;
	$fields['billing_address_2']['required']     = true;

	$fields['billing_phone']['placeholder']     = __( 'Số điện thoại', 'monamedia' );
	$fields['billing_address_2']['label']       = __( 'Địa chỉ nhận hàng', 'monamedia' );
	$fields['billing_address_2']['placeholder'] = __( 'Địa chỉ nhận hàng', 'monamedia' );
	$fields['billing_email']['placeholder']     = __( 'Email', 'monamedia' );
	$fields['billing_city']['placeholder']      = __( 'Chọn Quận / Huyện', 'monamedia' );
	$fields['billing_state']['placeholder']     = __( 'Chọn Tỉnh / Thành phố', 'monamedia' );
	$fields['billing_country']['placeholder']   = __( 'Xã / Phường', 'monamedia' );


//	$fields['billing_city']['type'] = 'select';

	return $fields;
}

// Woocommerce
define( 'MONA_WC_PRODUCTS', get_option( 'woocommerce_shop_page_id' ) );
define( 'MONA_WC_CART', get_option( 'woocommerce_cart_page_id' ) );
define( 'MONA_WC_CHECKOUT', get_option( 'woocommerce_checkout_page_id' ) );
define( 'MONA_WC_MYACCOUNT', get_option( 'woocommerce_myaccount_page_id' ) );
define( 'MONA_WC_THANKYOU', get_option( 'woocommerce_thanks_page_id' ) );


if ( ! session_id() ) {
	session_start();
}