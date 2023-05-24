<?php
add_filter('woocommerce_states', 'vietnam_cities_woocommerce');

function vietnam_cities_woocommerce($states)
{
    $states['VN'] = array(
        'CANTHO' => __('Cần Thơ', 'woocommerce'),
        'HCM' => __('Hồ Chí Minh', 'woocommerce'),
        'HANOI' => __('Hà Nội', 'woocommerce'),
        'HAIPHONG' => __('Hải Phòng', 'woocommerce'),
        'DANANG' => __('Đà Nẵng', 'woocommerce'),
        'ANGIAG' => __('An Giang', 'woocommerce'),
        'BRVT' => __('Bà Rịa - Vũng Tàu', 'woocommerce'),
        'BALIE' => __('Bạc Liêu', 'woocommerce'),
        'BACKAN' => __('Bắc Kạn', 'woocommerce'),
        'BACNINH' => __('Bắc Ninh', 'woocommerce'),
        'BACGIANG' => __('Bắc Giang', 'woocommerce'),
        'BENTRE' => __('Bến Tre', 'woocommerce'),
        'BDUONG' => __('Bình Dương', 'woocommerce'),
        'BDINH' => __('Bình Định', 'woocommerce'),
        'BPHUOC' => __('Bình Phước', 'woocommerce'),
        'BTHUAN' => __('Bình Thuận', 'woocommerce'),
        'CAMAU' => __('Cà Mau', 'woocommerce'),
        'DAKLAK' => __('Đak Lak', 'woocommerce'),
        'DAKNONG' => __('Đak Nông', 'woocommerce'),
        'DIENBIEN' => __('Điện Biên', 'woocommerce'),
        'ĐNAI' => __('Đồng Nai', 'woocommerce'),
        'GIALAI' => __('Gia Lai', 'woocommerce'),
        'HGIANG' => __('Hà Giang', 'woocommerce'),
        'HNAM' => __('Hà Nam', 'woocommerce'),
        'HTINH' => __('Hà Tĩnh', 'woocommerce'),
        'HDUONG' => __('Hải Dương', 'woocommerce'),
        'HUGIANG' => __('Hậu Giang', 'woocommerce'),
        'HOABINH' => __('Hòa Bình', 'woocommerce'),
        'HYEN' => __('Hưng Yên', 'woocommerce'),
        'KHOA' => __('Khánh Hòa', 'woocommerce'),
        'KGIANG' => __('Kiên Giang', 'woocommerce'),
        'KTUM' => __('Kom Tum', 'woocommerce'),
        'LCHAU' => __('Lai Châu', 'woocommerce'),
        'LAMDONG' => __('Lâm Đồng', 'woocommerce'),
        'LSON' => __('Lạng Sơn', 'woocommerce'),
        'LCAI' => __('Lào Cai', 'woocommerce'),
        'LAN' => __('Long An', 'woocommerce'),
        'NDINH' => __('Nam Định', 'woocommerce'),
        'NGAN' => __('Nghệ An', 'woocommerce'),
        'NBINH' => __('Ninh Bình', 'woocommerce'),
        'NTHUAN' => __('Ninh Thuận', 'woocommerce'),
        'PTHO' => __('Phú Thọ', 'woocommerce'),
        'PYEN' => __('Phú Yên', 'woocommerce'),
        'QBINH' => __('Quảng Bình', 'woocommerce'),
        'QNAM' => __('Quảng Nam', 'woocommerce'),
        'QNGAI' => __('Quảng Ngãi', 'woocommerce'),
        'QNINH' => __('Quảng Ninh', 'woocommerce'),
        'QTRI' => __('Quảng Trị', 'woocommerce'),
        'STRANG' => __('Sóc Trăng', 'woocommerce'),
        'SLA' => __('Sơn La', 'woocommerce'),
        'TNINH' => __('Tây Ninh', 'woocommerce'),
        'TBINH' => __('Thái Bình', 'woocommerce'),
        'TNGUYEN' => __('Thái Nguyên', 'woocommerce'),
        'THOA' => __('Thanh Hóa', 'woocommerce'),
        'TTHIEN' => __('Thừa Thiên - Huế', 'woocommerce'),
        'TGIANG' => __('Tiền Giang', 'woocommerce'),
        'TVINH' => __('Trà Vinh', 'woocommerce'),
        'TQUANG' => __('Tuyên Quang', 'woocommerce'),
        'VLONG' => __('Vĩnh Long', 'woocommerce'),
        'VPHUC' => __('Vĩnh Phúc', 'woocommerce'),
        'YBAI' => __('Yên Bái', 'woocommerce'),
    );

    return $states;
}

function mona_wc_get_order_item()
{
    $user = get_current_user_id();
    $args = array(
        'numberposts' => -1,
        'meta_key' => '_customer_user',
        'meta_value' => $user,
        'post_type' => 'shop_order',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'shop_order_status',
                'field' => 'slug',
                'terms' => 'completed'
            )
        )
    );
    $items = array();
    $posts_query = new WP_Query($args);
    if ($posts_query->have_posts()) {
        while ($posts_query->have_posts()) {
            $posts_query->the_post();
            $order = wc_get_order(get_the_ID());
            $order_items = $order->get_items();
            foreach ($order_items as $order_item) {
                $item_term = $items;
                if (isset($order_item['item_meta']['_product_id'])) {
                    $items = array_merge($order_item['item_meta']['_product_id'], $item_term);
                }
            }
        }
    }
    $items = array_unique($items);
    wp_reset_query();
    return $items;
}

function mona_wc_get_order_item_info()
{
    $user = get_current_user_id();
    $args = array(
        'numberposts' => -1,
        'meta_key' => '_customer_user',
        'meta_value' => $user,
        'post_type' => wc_get_order_types('view-orders'),
        'post_status' => array_keys(wc_get_order_statuses()),
    );
    $posts_query = new WP_Query($args);
    wp_reset_query();
    return $posts_query;
}

function mona_render_shop_order_columns($post_id)
{
    $post = get_post($post_id);

    $the_order = wc_get_order($post->ID);
?>
    <li class="mona-order-title">
        <div class="mona-order-e"><?php echo '#' . esc_attr($the_order->get_order_number()); ?></div>
    </li>
    <li class="mona-order-date">
        <div class="mona-order-e"><time><?php echo esc_attr($the_order->get_date_created()->date('d/m/Y')); ?></time></div>
    </li>
    <li class="mona-order-price">
        <div class="mona-order-e"><?php echo $the_order->get_formatted_order_total(); ?></div>
    </li>
    <?php
    if ($the_order->get_payment_method_title()) {
        echo '<li class="mona-order-method"><div class="mona-order-e">' . __('Via', 'woocommerce') . ' ' . esc_html($the_order->get_payment_method_title()) . '</div></li>';
    }
    ?>
    <li class="mona-order-status">
        <div class="mona-order-e"><?php echo wc_get_order_status_name($the_order->get_status()); ?></div>
    </li>
    <li class="mona-order-item-list">

        <?php
        $items = $the_order->get_items();
        if (is_array($items) && count($items) > 0) {
            foreach ($items as $item_id => $item) {
                $product = $item->get_product();
        ?>

                <ul class="order-items">
                    <li class="order-item">
                        <div class="order-item-thumb"><?php echo get_the_post_thumbnail($item['product_id']); ?></div>
                    </li>
                    <li class="order-item">
                        <div class="order-item-title"><a target="_blank" href="<?php echo get_the_permalink($item['product_id']); ?>"><?php echo ($item['name']); ?>

                            </a></div>
                    </li>
                    <li class="order-item">
                        <div class="order-item-price"><?php echo wc_price($the_order->get_item_total($item, false, true), array('currency' => $the_order->get_currency())); ?></div>
                    </li>
                    <li class="order-item">
                        <div class="order-item-discount">
                            <?php
                            if ($item->get_subtotal() !== $item->get_total()) {
                                echo '<span class="wc-order-item-discount">-' . wc_price(wc_format_decimal($the_order->get_item_subtotal($item, false, false) - $item->get_item_total($item_id, false, false), ''), array('currency' => $the_order->get_currency())) . '</span>';
                            }
                            ?>
                        </div>
                    </li>
                    <li class="order-item">
                        <div class="order-item-quantity">
                            <?php
                            echo '<small class="times">&times;</small> ' . esc_html($item->get_quantity());

                            if ($refunded_qty = $the_order->get_qty_refunded_for_item($item_id)) {
                                echo '<small class="refunded">' . ($refunded_qty * -1) . '</small>';
                            }
                            ?>
                        </div>
                    </li>
                    <li class="order-item">
                        <div class="order-item-total"><?php echo wc_price(wc_format_decimal($item->get_total()), array('currency' => $the_order->get_currency())); ?></div>
                    </li>
                </ul>
        <?php
            }
        }
        ?>

    </li>
<?php
}

function mona_ghn_get_distric_id()
{
    $active = get_option('giao_hang_nhanh_active');

    if ($active != 'yes') {
        return false;
    }
    $token = get_option('giao_hang_nhanh_api');
    if ($token == '') {
        return false;
    }
    $curl = curl_init();
    $test = get_option('giao_hang_nhanh_tester');
    if ($test == 'yes') {
        $url = GIAOHANGNHANH_API_TEST;
    } else {
        $url = GIAOHANGNHANH_API_LIVE;
    }
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . "master-data/district",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        // CURLOPT_POSTFIELDS => json_encode(array('token' => $token)),
    ));
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "token: $token",
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return false;
    }
    $response = json_decode($response, true);
    if (!isset($response['data']) || $response['data'] == 0) {
        return false;
    }
    return $response['data'];
    return false;
}
function mona_ghn_get_district_by_province( $provinceId ) {
    $active = get_option('giao_hang_nhanh_active');

    if ($active != 'yes') {
        return false;
    }
    $token = get_option('giao_hang_nhanh_api');
    if ($token == '') {
        return false;
    }

    $test = get_option('giao_hang_nhanh_tester');
    if ($test == 'yes') {
        $url = GIAOHANGNHANH_API_TEST;
    } else {
        $url = GIAOHANGNHANH_API_LIVE;
    }
    $url = $url . 'master-data/district';
    $dataRaw = ['province_id'=> $provinceId];
    $response = ghn_curl($url, $token , $dataRaw );
    if (isset($response['data'])) {
        return $response['data'];
    }
    return false;
}
function mona_ghn_get_province()
{
    $active = get_option('giao_hang_nhanh_active');

    if ($active != 'yes') {
        return false;
    }
    $token = get_option('giao_hang_nhanh_api');
    if ($token == '') {
        return false;
    }

    $test = get_option('giao_hang_nhanh_tester');
    if ($test == 'yes') {
        $url = GIAOHANGNHANH_API_TEST;
    } else {
        $url = GIAOHANGNHANH_API_LIVE;
    }
    $url = $url . 'master-data/province';
    $response = ghn_curl($url, $token);
    if (isset($response['data'])) {
        return $response['data'];
    }
    return false;
} 

function ghn_curl($url, $token , $dataRaw = [])
{
    $curl = curl_init();
    $arr  =  array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        // CURLOPT_POSTFIELDS => json_encode(array('token' => $token)),
    );
    if( count($dataRaw) > 0) { 
        $arr[CURLOPT_POSTFIELDS] = json_encode( $dataRaw );
    }
    curl_setopt_array($curl , $arr );
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "token: $token",
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return false;
    }
    $response = json_decode($response, true);
    return $response;
}

function mona_ghn_get_service_id()
{
    $active = get_option('giao_hang_nhanh_active');
    if ($active != 'yes') {
        return;
    }
    $token = get_option('giao_hang_nhanh_api');
    if ($token == '') {
        return false;
    }
    $test = get_option('giao_hang_nhanh_tester');
    if ($test == 'yes') {
        $url = GIAOHANGNHANH_API_TEST;
    } else {
        $url = GIAOHANGNHANH_API_LIVE;
    }
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . "master-data/district",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        // CURLOPT_POSTFIELDS => json_encode(array('token' => $token)),
    ));

    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "token: $token",
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return false;
    }
    $response = json_decode($response, true);
    if (!isset($response['data']) || $response['data'] == 0) {
        return false;
    }
    return $response['data'];
    return false;
}

function mona_ghn_order_tracking($label)
{
    $active = get_option('giao_hang_nhanh_active');
    $send_order = get_option('giao_hang_nhanh_send_order');
    if ($active != 'yes' || $send_order != 'yes') {
        return;
    }
    $token = get_option('giao_hang_nhanh_api');
    if ($token == '') {
        return false;
    }
    $test = get_option('giao_hang_nhanh_tester');
    if ($test == 'yes') {
        $url = GIAOHANGNHANH_API_TEST;
    } else {
        $url = GIAOHANGNHANH_API_LIVE;
    }
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . "v2/shipping-order/detail",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode(array('token' => $token, 'OrderCode' => $label)),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return $err;
    }
    $response = json_decode($response, true);
    if ($response['msg'] == 'Success') {
        return $response['data']['CurrentStatus'];
    }
    return 'Không tồn tại đơn hàng trên Giao Hàng Nhanh';
}

function mona_ghtk_order_tracking($label)
{
    $active = get_option('giao_hang_tiet_kiem_active');
    $send_order = get_option('giao_hang_tiet_kiem_send_order');
    if ($active != 'yes' || $send_order != 'yes') {
        return false;
    }
    $token = get_option('giao_hang_tiet_kiem_api');
    if ($token == '') {
        return false;
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://services.giaohangtietkiem.vn/services/shipment/v2/" . $label,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_HTTPHEADER => array(
            "Token: " . $token,
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $response = json_decode($response, true);
    if ($response['success'] == true) {
        return $response['order']['status_text'];
    }
    return 'Không tồn tại đơn hàng trên Giao Hàng Tiết Kiệm';
}
?>