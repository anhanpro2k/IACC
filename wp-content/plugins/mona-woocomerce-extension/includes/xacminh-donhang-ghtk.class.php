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
class Mona_xacminh_ghtk_donhang {

    protected $custom_method ='giao_hang_tiet_kiem';
    protected $url_send_order = "https://services.giaohangtietkiem.vn/services/shipment/order";
    protected $token;

    public function __construct() {
        $active = get_option('giao_hang_tiet_kiem_active');
        $send_order = get_option('giao_hang_tiet_kiem_send_order');
        if($active !='yes' || $send_order !='yes'){
            return;
        }
        $this->token = get_option('giao_hang_tiet_kiem_api');
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

    public function render_args($order) {
        $items = $order->get_items();

        $args = array('products' => array());
        if (is_array($items) && count($items) > 0) {
            foreach ($items as $item) {
                $w = get_post_meta($item->get_product_id(), '_weight', true);
                $quantity = $item['quantity'];
                $weight = (float) $w * (int) $quantity;
                $args['products'][] = array(
                    'name' => $item->get_name(),
                    'weight' => $weight
                );
            }
            $pick_name = get_option('ghtk_pick_name');
            $pick_tel = get_option('ghtk_pick_tel');
            $pick_email = get_option('ghtk_pick_email');
            $pick_province = get_option('ghtk_pick_province');
            $pick_district = get_option('ghtk_pick_district');
            $pick_ward = get_option('ghtk_pick_ward');
            $pick_street = get_option('ghtk_pick_street');
            $pick_address = get_option('ghtk_pick_address');
            $state = str_replace('-', ' ', $order->get_shipping_state());
            
            $mss = array();
            if ($this->token == '' || $pick_name == '' || $pick_tel == '' || $pick_email == '' || $pick_province == '' || $pick_district == '' || $pick_ward == '' || $pick_street == '' || $pick_address == '') {
                $mss[] = 'Bạn chưa config đầy đủ thông tin bắt buột ở trang cài đặt';
            }
            if ($order->get_shipping_first_name() == '' && $order->get_shipping_last_name() == '') {
                $mss[] = 'Không xác dịnh được người nhận hàng';
            }
            if ($order->get_shipping_address_1() == '') {
                $mss[] = 'Không xác dịnh được đia chỉ người nhận';
            }
            if ($state == '') {
                $mss[] = 'Không xác dịnh được tình/ thành phố người nhận';
            }
            if ($order->get_shipping_city() == '') {
                $mss[] = 'Không xác dịnh được quận/huyện phố người nhận';
            }
            if ($order->get_billing_phone() == '') {
                $mss[] = 'Không xác dịnh được số điện thoại người nhận';
            }
            if ($order->get_billing_email() == '') {
                $mss[] = 'Không xác dịnh được Email người nhận';
            }
            if (count($mss) > 0) {
                update_post_meta($order->get_id(), '__order_mss', $mss);
                return false;
            }
            
            $args['order'] = array(
                'id' => $order->get_id(),
                'pick_name' => $pick_name,
                "pick_province" => $pick_province,
                "pick_district" => $pick_district,
                "pick_ward" => $pick_ward,
                "pick_street" => $pick_street,
                "pick_address" => $pick_address,
                'pick_tel' => $pick_tel,
                'name' => @$order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
                'address' => $order->get_shipping_address_1(),
                'province' => $state,
                'district' => $order->get_shipping_city(),
                'tel' => $order->get_billing_phone(),
                'email' => $order->get_billing_email(),
                'return_name' => $pick_name,
                'return_address' => $pick_address,
                'return_province' => $pick_province,
                'return_district' => $pick_district,
                'return_street' => $pick_street,
                'return_tel' => $pick_tel,
                'return_email' => $pick_email,
                'is_freeship' => 1,
                'value' => $order->get_total(),
                'pick_money' => $order->get_total(),
            );
            if ($order->get_payment_method() == 'cod') {
                $args['order']['pick_money'] = $order->get_total();
            }
            $send = $this->send_order($args);
            if ($send['success'] == true) {
                update_post_meta($order->get_id(), '__ghtk_order_label', $send['order']['label']);
            } else {
                if (isset($send['error']) && $send['error']['code'] == 'ORDER_ID_EXIST') {
                    update_post_meta($order->get_id(), '__ghtk_order_label', $send['error']['ghtk_label']);
                } else {
                    update_post_meta($order->get_id(), '__order_mss', array('Không gửi được order lên giao hàng tiết kiệm'));
                }
            }
        }
        return false;
    }

    public function send_order($order) {
        $order = json_encode($order,JSON_UNESCAPED_UNICODE);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_send_order,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $order,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Token: " . $this->token,
                "Content-Length: " . strlen($order),
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response,true);
       
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
