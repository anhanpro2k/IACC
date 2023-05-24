<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of xacminh-donhang
 *
 * @author Hy
 */
class Mona_xacminh_ghn_donhang {

    protected $custom_method = 'giao_hang_nhanh';
    protected $url_send_order;
    protected $token;

    public function __construct() {
            $active = get_option('giao_hang_nhanh_active');
    $send_order = get_option('giao_hang_nhanh_send_order');
    if ($active != 'yes'|| $send_order !='yes') {
        return;
    }
        $this->token = get_option('giao_hang_nhanh_api');
        $test = get_option('giao_hang_nhanh_tester');
        if ($test == 'yes') {
            $url = GIAOHANGNHANH_API_TEST;
        } else {
            $url = GIAOHANGNHANH_API_LIVE;
        }
        $this->url_send_order= $url."v2/shipping-order/create";
    }

    public function mona_xacminh_action($order_id) {
        $order = wc_get_order($order_id);
        $check = $this->check_has_shipping($order);
        if ($check == false) {
            return false;
        }
        $args = $this->render_args($order);
        if ($args == false) {
            return false;
        }
        return $args;
    }

    public function get_distric_id($name) {
        $token = $this->token;
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
            CURLOPT_URL => $url."master-data/district",
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
        foreach ($response['data'] as $data) {
            if ($data['DistrictName'] == $name) {
                return $data['DistrictID'];
            }
        }
        return false;
    }

    public function render_args($order) {
        $items = $order->get_items();
        $city = $order->get_shipping_city();
        $to_distric_id = $this->get_distric_id($city);
        $from_distric_id = get_option('ghn_pick_district');
        $from_address = get_option('ghn_pick_address');
        $sevice_id = get_option('ghn_service_id');
        $name = get_option('ghn_contact_name');
        $phone = get_option('ghn_contact_phone');
        $mss = array();
        if ($this->token == '' || $from_distric_id == '' || $from_address == '' || $sevice_id == '' || $name == '' || $phone == '') {
            $mss[] = 'Bạn chưa config đầy đủ thông tin bắt buột ở trang cài đặt';
        }
        if ($order->get_shipping_first_name() == '' && $order->get_shipping_last_name() == '') {
            $mss[] = 'Không xác dịnh được người nhận hàng';
        }
        if ($to_distric_id == '' || $to_distric_id == false) {
            $mss[] = 'Không xác dịnh được đia chỉ người nhận';
        }
        if ($order->get_billing_phone() == '') {
            $mss[] = 'Không xác dịnh được số điện thoại người nhận';
        }
        if ($order->get_shipping_address_1() == '') {
            $mss[] = 'Không xác dịnh được địa chỉ người nhận';
        }
        if (count($mss) > 0) {
            update_post_meta($order->get_id(), '__order_mss', $mss);
            return false;
        }
        $weight = 0;
        $width = 0;
        $height = 0;
        $lengh = 0;
        foreach ($items as $item) {
            $wei = get_post_meta($item->get_product_id(), '_weight', true);
            $hei = get_post_meta($item->get_product_id(), '_height', true);
            $len = get_post_meta($item->get_product_id(), '_length', true);
            $wid = get_post_meta($item->get_product_id(), '_width', true);
            $quantity = $item['quantity'];
            $weight += (float) $wid * (int) $quantity;
            $width += (float) $w * (int) $quantity;
            $height += (float) $hei * (int) $quantity;
            $lengh += (float) $len * (int) $quantity;
        }
        if ($weight == 0) {
            $weight = 1;
        }
        if ($lengh == 0) {
            $lengh = 1;
        }
        if ($width == 0) {
            $width = 1;
        }
        if ($height == 0) {
            $height = 1;
        }

        $data = array(
            'token' => $this->token,
            'FromDistrictID' => (int) $from_distric_id,
            'ToDistrictID' => (int) $to_distric_id,
            'ClientContactName' => $name,
            'ClientContactPhone' => $phone,
            'ClientAddress' => $from_address,
            'CustomerName' => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
            'CustomerPhone' => $order->get_billing_phone(),
            'ShippingAddress' => $order->get_shipping_address_1(),
            // 'ClientHubID' => (int)$exp[1],
            'ServiceID' => (int) $sevice_id,
            'Weight' => (float) $weight,
            'Length' => (float) $lengh,
            'Width' => (float) $width,
            'Height' => (float) $height,
            'NoteCode' => 'CHOXEMHANGKHONGTHU',
        );
        if ($order->get_payment_method() == 'cod') {
            $data['CoDAmount'] = (float) $order->get_total();
        }
        $send = $this->send_order($data);
        if ($send['msg'] == 'Success') {
            update_post_meta($order->get_id(), '__ghtk_order_label', $send['data']['OrderCode']);
        } else {
            update_post_meta($order->get_id(), '__order_mss', array('Không gửi được order lên giao hàng nhanh'));
        }
        return false;
    }

    public function send_order($order) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_send_order,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($order),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $response = json_decode($response, true);

        return $response;
    }

    public function check_has_shipping($order) {
        $shipping_method = $order->get_shipping_methods();
        foreach ($shipping_method as $mt) {

            $c = strpos($mt->get_method_id(), $this->custom_method);
            if (0 === $c) {
                return true;
            }
        }
        return false;
    }

}
