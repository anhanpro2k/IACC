<?php

function mona_method_filter($label, $method)
{
    $custom_method = json_decode(MONA_SHIPPING_METHOD);

    if (in_array($method->id, $custom_method)) {
        $label = $method->get_label();
    }
    return $label;
}

add_filter('woocommerce_cart_shipping_method_full_label', 'mona_method_filter', 10, 2);

function disable_shipping_calc_on_cart($show_shipping)
{
    if (is_cart()) {
        return false;
    }
    return $show_shipping;
}

add_filter('woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99);

function mona_itit()
{
    update_option('woocommerce_enable_shipping_calc', '0');
    update_option('woocommerce_shipping_cost_requires_address', 'yes');
    update_option('woocommerce_ship_to_destination', 'billing_only');
    update_option('woocommerce_ship_to_billing_address_only', 'yes');
}

add_action('init', 'mona_itit');

function my_change_status_function($order_id)
{
    $custom_method = json_decode(MONA_SHIPPING_METHOD);
    $order = wc_get_order($order_id);
    $active = get_option('giao_hang_tiet_kiem_active');
    $active2 = get_option('giao_hang_nhanh_active');
    if ($active != 'yes' && $active2 != 'yes') {
        return $order_id;
    }
    foreach ($custom_method as $method) {
        if ($order->has_shipping_method($method)) {
            $payment = $order->get_payment_method();
            if ($payment == 'bacs' || $payment == 'cheque' || $payment == 'cod') {
                // $order->update_status('chuaxacminh');
            } else {
                // $order->update_status('xacminh');
            }
            break;
        }
    }
}

add_action('woocommerce_thankyou', 'my_change_status_function');



function register_awaiting_shipment_order_status($order_statuses)
{
    $order_statuses['wc-xacminh'] = array(
        'label' => _x('Xác Thực Đơn Hàng', 'Order status', 'woocommerce'),
        'public' => false,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Xác Thực Đơn Hàng', 'woocommerce'),
    );
    $order_statuses['wc-chuaxacminh'] = array(
        'label' => _x('Chưa Xác Thực Đơn Hàng', 'Order status', 'woocommerce'),
        'public' => false,
        'exclude_from_search' => false,
        'show_in_admin_all_list' => true,
        'show_in_admin_status_list' => true,
        'label_count' => _n_noop('Chưa Xác Thực Đơn Hàng', 'woocommerce'),
    );
    return $order_statuses;
}

// add_filter('woocommerce_register_shop_order_post_statuses', 'register_awaiting_shipment_order_status', 10, 1);

function add_awaiting_shipment_to_order_statuses($order_statuses)
{

    $new_order_statuses = array();
    $new_order_statuses['wc-chuaxacminh'] = 'Chưa Xác Thực Đơn Hàng';
    $new_order_statuses['wc-xacminh'] = ' Đã Xác Thực Đơn Hàng';
    // add new order status after processing
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
    }

    return $new_order_statuses;
}

// add_filter('wc_order_statuses', 'add_awaiting_shipment_to_order_statuses');

function mona_order_xacminh_status($order_id)
{
    $active = get_option('giao_hang_tiet_kiem_active');
    $active2 = get_option('giao_hang_nhanh_active');
    if ($active != 'yes' && $active2 != 'yes') {
        return $order_id;
    }
    $order = wc_get_order($order_id);
    $shipping_method = $order->get_shipping_methods();
    foreach ($shipping_method as $mt) {
        $cghtk = strpos($mt->get_method_id(), 'giao_hang_tiet_kiem');
        $cghn = strpos($mt->get_method_id(), 'giao_hang_nhanh');
        if (0 === $cghtk) {
            // require_once(MONA_EXTENSION_PATCH . 'includes/xacminh-donhang-ghtk.class.php');
            // $xacminh = new Mona_xacminh_ghtk_donhang();
            // $args = $xacminh->mona_xacminh_action($order_id);
            // return;
        }
        if (0 === $cghn) {
            // require_once(MONA_EXTENSION_PATCH . 'includes/xacminh-donhang-ghn.class.php');
            // $xacminh = new Mona_xacminh_ghn_donhang();
            // $args = $xacminh->mona_xacminh_action($order_id);
            // return;
        }
    }
}

// add_action('woocommerce_order_status_xacminh', 'mona_order_xacminh_status', 10, 1);

function mona_remove_noitic($order_id)
{
    update_post_meta($order_id, '__order_mss', array());
}

// add_action('woocommerce_order_status_chuaxacminh', 'mona_remove_noitic', 10, 1);
add_action('woocommerce_order_status_pending', 'mona_remove_noitic', 10, 1);
add_action('woocommerce_order_status_processing', 'mona_remove_noitic', 10, 1);
add_action('woocommerce_order_status_on-hold', 'mona_remove_noitic', 10, 1);
add_action('woocommerce_order_status_completed', 'mona_remove_noitic', 10, 1);
add_action('woocommerce_order_status_cancelled', 'mona_remove_noitic', 10, 1);
add_action('woocommerce_order_status_refunded', 'mona_remove_noitic', 10, 1);
add_action('woocommerce_order_status_failed', 'mona_remove_noitic', 10, 1);

function mona_display_order_noiti($order)
{
    $order_id = $order->get_id();
    $meta = get_post_meta($order_id, '__order_mss', true);
    if (is_array($meta)) {
        foreach ($meta as $v) {
            echo '<p class="notice notice-error clear ">' . $v . '</p>';
        }
    }
}

add_action('woocommerce_admin_order_data_after_billing_address', 'mona_display_order_noiti', 10, 1);

function mona_shipping_display($order)
{
    $active = get_option('giao_hang_tiet_kiem_active');
    $send_order = get_option('giao_hang_tiet_kiem_send_order');
    if ($active != 'yes' || $send_order != 'yes') {
        return $order;
    }
    $order_id = $order->get_id();
    $meta = get_post_meta($order_id, '__ghtk_order_label', true);
    if ($meta != '') {
?>
        <div class="clear">
            <h3>Thông tin Shipping</h3>
            <p><strong><?php echo $order->get_shipping_method(); ?></strong>: <?php echo $meta; ?></p>
        </div>
    <?php
    }
}

add_action('woocommerce_admin_order_data_after_shipping_address', 'mona_shipping_display', 10, 1);

function mona_display_order_shipping($order)
{
    $active = get_option('giao_hang_tiet_kiem_active');
    $active2 = get_option('giao_hang_nhanh_active');
    if ($active != 'yes' && $active2 != 'yes') {
        return $order;
    }
    $order_id = $order->get_id();
    $meta = get_post_meta($order_id, '__ghtk_order_label', true);
    if ($meta != '') {
    ?>
        <section class="mona-shipping-status">
            <h2 class="woocommerce-column__title">Thông tin Shipping</h2>
            <?php
            $shipping_method = $order->get_shipping_methods();

            foreach ($shipping_method as $mt) {

                $cghtk = strpos($mt->get_method_id(), 'giao_hang_tiet_kiem');
                if (0 === $cghtk && $active == 'yes') {
                    $label = mona_ghtk_order_tracking($meta);
            ?>
                    <div class="label"><strong>Qua Giao Hàng Tiết Kiệm</strong></div>
                    <div class="status">Tình Trạng: <?php echo $label; ?></div>
                <?php
                    return;
                }
                $cghn = strpos($mt->get_method_id(), 'giao_hang_nhanh');

                if (0 === $cghn && $active2 == 'yes') {
                    $label = mona_ghn_order_tracking($meta);
                ?>
                    <div class="label"><strong>Qua Giao Hàng Nhanh</strong></div>
                    <div class="status">Tình Trạng: <?php echo $label; ?></div>
            <?php
                    return;
                }
            }
            ?>
        </section><?php
                }
            }

            add_action('woocommerce_order_details_after_order_table', 'mona_display_order_shipping');
