<?php

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    function mona_shipping_custom()
    {
        require_once(MONA_EXTENSION_PATCH . 'includes/shipping/giao-hang-nhanh.class.php');
        require_once(MONA_EXTENSION_PATCH . 'includes/shipping/giao-hang-tiet-kiem.class.php');
        require_once(MONA_EXTENSION_PATCH . 'includes/shipping/vnpost.class.php');
        require_once(MONA_EXTENSION_PATCH . 'includes/shipping/viettel-post.class.php');
    }

    add_action('woocommerce_shipping_init', 'mona_shipping_custom');

    function Mona_shipping_method($methods)
    {
        $methods['giao_hang_nhanh'] = 'Mona_Shipping_giao_hang_nhanh';
        $methods['giao_hang_tiet_kiem'] = 'Mona_Shipping_giao_hang_tiet_kiem';
        $methods['vn_post'] = 'Mona_Shipping_VN_POST';
        $methods['viettel_post'] = 'Mona_Shipping_Viettel_POST';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'Mona_shipping_method');
}
