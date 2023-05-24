<?php

class Mona_Shipping_giao_hang_nhanh extends WC_Shipping_Method
{

    /** @var string cost passed to [fee] shortcode */
    protected $fee_cost = '';
    protected $_token;
    protected $_shop_id;


    /**
     * Constructor.
     *
     * @param int $instance_id
     */
    public function __construct($instance_id = 0)
    {
        $this->_shop_id = 1573959;
        $active = get_option('giao_hang_nhanh_active');
        if ($active != 'yes') {
            return;
        }
        $this->_token = get_option('giao_hang_nhanh_api');
        $this->id = 'giao_hang_nhanh';
        $this->instance_id = absint($instance_id);
        $this->method_title = __('Giao Hàng Nhanh', 'monamedia');
        $this->method_description = __('Giao Hàng Qua <a target="_blank" href="https://giaohangnhanh.vn">Giao Hàng Nhanh</a>', 'monamedia');
        $this->supports = array(
            'shipping-zones',
            'instance-settings',
            'instance-settings-modal',
        );
        $this->init();

        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
    }

    /**
     * init user set variables.
     */
    public function init()
    {
        $settings = array(
            'title' => array(
                'title' => __('Tên Hiển Thị', 'monamedia'),
                'type' => 'text',
                'description' => __('Tên Hiển Thị', 'monamedia'),
                'default' => __('Giao Hàng Nhanh', 'monamedia'),
                'desc_tip' => true,
            ),
        );
        $this->instance_form_fields = $settings;
        $this->title = $this->get_option('title');
        $this->tax_status = 'taxable';
        $this->cost = 0;
        $this->type = $this->get_option('type', 'class');
    }

    /**
     * Evaluate a cost from a sum/string.
     * @param  string $sum
     * @param  array  $args
     * @return string
     */
    protected function evaluate_cost($sum, $args = array())
    {
        include_once(WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php');

        // Allow 3rd parties to process shipping cost arguments
        $args = apply_filters('woocommerce_evaluate_shipping_cost_args', $args, $sum, $this);
        $locale = localeconv();
        $decimals = array(wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',');
        $this->fee_cost = $args['cost'];

        // Expand shortcodes
        add_shortcode('fee', array($this, 'fee'));

        $sum = do_shortcode(str_replace(
            array(
                '[qty]',
                '[cost]',
            ),
            array(
                $args['qty'],
                $args['cost'],
            ),
            $sum
        ));

        remove_shortcode('fee', array($this, 'fee'));

        // Remove whitespace from string
        $sum = preg_replace('/\s+/', '', $sum);

        // Remove locale from string
        $sum = str_replace($decimals, '.', $sum);

        // Trim invalid start/end characters
        $sum = rtrim(ltrim($sum, "\t\n\r\0\x0B+*/"), "\t\n\r\0\x0B+-*/");
        // Do the math
        return $sum ? WC_Eval_Math::evaluate($sum) : 0;
    }

    /**
     * Work out fee (shortcode).
     * @param  array $atts
     * @return string
     */
    public function fee($atts)
    {
        $atts = shortcode_atts(array(
            'percent' => '',
            'min_fee' => '',
            'max_fee' => '',
        ), $atts, 'fee');

        $calculated_fee = 0;

        if ($atts['percent']) {
            $calculated_fee = $this->fee_cost * (floatval($atts['percent']) / 100);
        }

        if ($atts['min_fee'] && $calculated_fee < $atts['min_fee']) {
            $calculated_fee = $atts['min_fee'];
        }

        if ($atts['max_fee'] && $calculated_fee > $atts['max_fee']) {
            $calculated_fee = $atts['max_fee'];
        }

        return $calculated_fee;
    }

    /**
     * calculate_shipping function.
     *
     * @param array $package (default: array())
     */
    public function calculate_shipping($package = array())
    {

        $cost = $this->render_shipping_price($package);
        if ($cost == 0 || $cost == '') {
            return false;
        }
        $rate = array(
            'id' => $this->get_rate_id(),
            'label' => $this->title,
            'cost' => $cost,
            'package' => $package,
        );

        $this->add_rate($rate);
        do_action('woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate);
    }

    public function get_distric_id($name)
    {
        $token = $this->_token;
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
            CURLOPT_POSTFIELDS => json_encode(array('token' => $token)),
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

    public function render_shipping_price($package)
    {
        $token = $this->_token;
        if ($token == '') {
            return 0;
        }

        global $woocommerce;
        $items = $woocommerce->cart->get_cart();
        if (is_array($items) && count($items) > 0) {
            $contry = WC()->customer->get_shipping_country();
            $city = WC()->customer->get_shipping_city();
            $to_district_id = $this->get_distric_id($city);

            $from_district = get_option('ghn_pick_district');

            if ($contry != 'VN') {
                return 0;
            }
            if ($city == '' || $to_district_id == false) {
                return 0;
            }
            if ($from_district == '') {
                return 0;
            }
            $districtArr = explode('-', $from_district);
            $districtId = $districtArr[0];
            $provinceId = $districtArr[1];
            $text_ward_code = $package['destination']['address_1'];
            // var_dump($_POST['m_to_ward_code']);

            $to_ward_code = $this->get_to_ward_code($to_district_id, $text_ward_code);

            $weight = $length = $width = $height = 20;
            foreach ($items as $cart_item) {
                // $_product = apply_filters('woocommerce_cart_item_product', @$cart_item['data']);
                $quantity = $cart_item['quantity'];
                $product = $cart_item['data'];
                $w = (float) $product->get_weight();
                // $w = (int) get_post_meta($cart_item['product_id'], '_weight', true) ;
                // if( get_current_user_id(  ) == 1 ) 
                    // var_dump($w);
                $w = $w * 1000;
                $weight += (float) $w * (int) $quantity;
            }
            $service_id =  $this->get_service_id($districtId, $to_district_id);
            $data = array(
                'token' => $token,
                'from_district_id' => (int) $districtId,
                'to_district_id' => (int) $to_district_id,
                'service_id' => (int) $service_id,
                'to_ward_code' => $to_ward_code,
                'weight' => $weight,
                // 'length' => $length,
                // 'width' => $width,
                // 'height' => $height,
            );
            // if( get_current_user_id(  ) == 1 ) 
            //     var_dump($data);
            $test = get_option('giao_hang_nhanh_tester');
            if ($test == 'yes') {
                $url = GIAOHANGNHANH_API_TEST;
            } else {
                $url = GIAOHANGNHANH_API_LIVE;
            }
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url . "v2/shipping-order/fee",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data),
            ));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "token: $token",
                "ShopId: $this->_shop_id",
                "Content-Type: text/plain",
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
            if ($err) {

                return 0;
            }
            $response = json_decode($response, true);
            // var_dump($response);
            if (@$response['message'] == 'Success') {
                // var_dump($response['data']['total']);
                $f = (float) $response['data']['total'];
                return $f;
            }
        }
        return 0;
    }
    public function get_service_id($from_district, $to_district)
    {
        $token = $this->_token;
        $test = get_option('giao_hang_nhanh_tester');
        if ($test == 'yes') {
            $url = GIAOHANGNHANH_API_TEST;
        } else {
            $url = GIAOHANGNHANH_API_LIVE;
        }
        $curl = curl_init();

        $data = array(
            'token' => $token,
            'from_district' => (int) $from_district,
            'to_district' => (int) $to_district,
            'shop_id' => $this->_shop_id,
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "v2/shipping-order/available-services",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "token: $token",
            "ShopId: $this->_shop_id",
            "Content-Type: text/plain",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {

            return 0;
        }
        $response = json_decode($response, true);
        if ($response['code'] == '200') {
            $data = $response['data'];
            if( is_array($data )) { 
                foreach ($data as $k =>$value ) { 
                    return $value['service_id'];
                }
            }
        }
        return false; 
    }

    public function get_to_ward_code($district_id, $name)
    {
        $token = $this->_token;
        $test = get_option('giao_hang_nhanh_tester');
        if ($test == 'yes') {
            $url = GIAOHANGNHANH_API_TEST;
        } else {
            $url = GIAOHANGNHANH_API_LIVE;
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . "master-data/ward?district_id=" . $district_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "token: $token",
            "ShopId: $this->_shop_id",
            "Content-Type: text/plain",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {

            return 0;
        }
        $response = json_decode($response, true);
        if ($response['code'] == '200') {
            $data = $response['data'];
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    if (in_array($name, $value['NameExtension'])) {
                        return $value['WardCode'];
                    }
                }
            }
        }
        return 0;
    }
    /**
     * Get items in package.
     * @param  array $package
     * @return int
     */
    public function get_package_item_qty($package)
    {
        $total_quantity = 0;
        foreach ($package['contents'] as $item_id => $values) {
            if ($values['quantity'] > 0 && $values['data']->needs_shipping()) {
                $total_quantity += $values['quantity'];
            }
        }
        return $total_quantity;
    }

    /**
     * Finds and returns shipping classes and the products with said class.
     * @param mixed $package
     * @return array
     */
    public function find_shipping_classes($package)
    {
        $found_shipping_classes = array();

        foreach ($package['contents'] as $item_id => $values) {
            if ($values['data']->needs_shipping()) {
                $found_class = $values['data']->get_shipping_class();

                if (!isset($found_shipping_classes[$found_class])) {
                    $found_shipping_classes[$found_class] = array();
                }

                $found_shipping_classes[$found_class][$item_id] = $values;
            }
        }

        return $found_shipping_classes;
    }
}
