<?php
class Mona_Shipping_Viettel_POST extends WC_Shipping_Method
{

    /** @var string cost passed to [fee] shortcode */
    protected $fee_cost = '';
    protected $_url_api;
    protected $_user;
    protected $_password;
    protected $_live;
    protected $_SenderDistrictId;
    protected $_SenderProvinceId;
    protected $_token;


    /**
     * Constructor.
     * key active: m_viettel_post_active
     * key id: viettel_post
     *
     * @param int $instance_id
     */
    public function __construct($instance_id = 0)
    {
        $active = get_option('m_viettel_post_active');
        // var_dump($active);
        // exit;
        if ($active != 'yes') {
            return;
        }
        $this->_user = get_option('m_viettel_post_user_name');
        $this->_password = get_option('m_viettel_post_user_password');

        if ($this->_password == '' or $this->_user == '') {
            return;
        }


        $live = get_option('m_viettel_post_products');
        $this->_live = false;
        if ($live == 'yes') {
            $this->_live = true;
        }
        $SenderProvinceId = get_option('m_viettel_post_provinces_user');
        $SenderDistrictId = get_option('m_viettel_post_district_user');
        if ($SenderDistrictId == '' or $SenderProvinceId == '') {
            return;
        }
        $this->_SenderProvinceId = $SenderProvinceId;
        $this->_SenderDistrictId = $SenderDistrictId;

        $this->_url_api = 'https://transporter.viettelsale.com/v1/';
        if ($this->_live) {
            $this->_url_api = 'https://transporter.viettelsale.com/v1/';
        }
        $token = $this->get_token();

        if ($token == '') {
            return;
        }
        $this->_token = $token;
        $this->id = 'viettel_post';
        $this->instance_id = absint($instance_id);
        $this->method_title = __('Viettel Post', 'monamedia');
        $this->method_description = __('Giao Hàng Qua <a target="_blank" href="https://viettelpost.com.vn">Viettel Post</a>', 'monamedia');
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
                'default' => __('VNPost: Bưu điện Việt Nam', 'monamedia'),
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
            'label' => 'Viettel Post',
            'cost' => $cost,
            'package' => $package,
        );
        $this->add_rate($rate);
        do_action('woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate);
    }

    public function render_shipping_price($package)
    {
        $active = get_option('m_viettel_post_active');
        if ($active != 'yes') {
            return 0;
        }
        $token = $this->_token;
        if (!$token and $token == '') {
            return 0;
        }

        $items = wc()->cart->get_cart();
        if (is_array($items) && count($items) > 0) {
            $address = WC()->customer->get_shipping_address();
            $city = WC()->customer->get_shipping_city();
            $stated = WC()->customer->get_shipping_state();
            $country = WC()->customer->get_shipping_country();
            if ($country != 'VN') {
                return 0;
            }
            if ($address == '') {
                return 0;
            }
            if ($city == '') {
                return 0;
            }
            if ($stated == '') {
                return 0;
            }

            $province = $this->slugify($stated);
            $district = $this->slugify($city);
            $Receiver = $this->get_province_id($province, $district);
       
            if (!$Receiver) {
                return;
            }

            $weight = 1;
            $width =  1;
            $length = 1;
            $height =  1;
            $OrderAmount = $package['cart_subtotal'];
            $dataQuantity = 0;
            foreach ($items as $cart_item) {
                $quantity = $cart_item['quantity'];
                $product = $cart_item['data'];
                $w = (float) $product->get_weight();
                $w = (float) $w * 1000;
                $weight += (float) $w * (int) $quantity;
                $dataQuantity += $cart_item['quantity'];
            }
            $SenderDistrictId  = $this->_SenderDistrictId; # quận người gửi
            $SenderProvinceId  = $this->_SenderProvinceId; # tỉnh người gửi

            $ReceiverDistrictId  =  $Receiver['MaQuanHuyen']; # người nhận
            $ReceiverProvinceId  =  $Receiver['MaTinhThanh']; # người nhận 
            $service = 'PHS';
            if ($stated != 'HO-CHI-MINH') {
                $service = 'LCOD';
            }
            $service = 'LCOD';
            // var_dump($service);
            $dataBody = [
                "SENDER_PROVINCE" => $SenderDistrictId,
                "SENDER_DISTRICT" => $SenderProvinceId,
                "RECEIVER_PROVINCE" => $ReceiverProvinceId,
                "RECEIVER_DISTRICT" => $ReceiverDistrictId,
                "PRODUCT_TYPE" => "HH",
                "PRODUCT_WEIGHT" => $weight,
                "PRODUCT_LENGTH" => $length,
                "PRODUCT_WIDTH" => $width,
                "PRODUCT_HEIGHT" => $height,
                "PRODUCT_PRICE" => (float) $OrderAmount,
                "PRODUCT_QUANTITY" => $dataQuantity,
                "ORDER_SERVICE" => $service,
                "MONEY_COLLECTION" => 0,
            ]; 
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://transporter.viettelsale.com/v1/pricing/detail',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($dataBody),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: $this->_token",
                    'Content-Type: application/json'
                ),
            ));

            $response = curl_exec($curl);
       
            curl_close($curl);
            $err = curl_error($curl);

            if ($err) {
                return;
            } else {
                $result = json_decode($response, true);
                if ($result['status'] == 200) {
                    return $result['data']['MONEY_TOTAL'];
                }
            }
        }
        return 0;
    }
    function get_province_id($string, $address)
    {
        $address = $this->change_name($address);
        $url = "/location/provinces";
        $headers =  [
            "cache-control: no-cache",
            "content-type: application/json",
            "Authorization: $this->_token",
        ];

        $response = $this->call_cURL($url, "GET", $headers, false);

        $result = false;
        if ($response['status'] == 200) {

            $data = json_decode($response['data']);

            if (is_array($data->data)) {
                foreach ($data->data as $key => $value) {
                    $name = $this->slugify($value->PROVINCE_NAME);
                    if (strpos($string, $name) or $string == $name) {
                        $result = ['MaTinhThanh' => $value->PROVINCE_ID];

                        $urlDistrict = "location/districts?provinceId=$value->PROVINCE_ID";
                        $responseDistrict = $this->call_cURL($urlDistrict, "GET", $headers, false);
                        if ($responseDistrict['status'] == 200) {
                            $data = json_decode($responseDistrict['data']);
                            if (is_array($data->data)) {
                                foreach ($data->data as $k => $district) {
                                    $nameDis  = $this->slugify($district->DISTRICT_NAME);
                                   
                                    if (strpos($address, $nameDis) or $address == $nameDis) {
                                        $result['MaQuanHuyen'] = $district->DISTRICT_ID;
                                        return $result;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function get_token()
    {
        $url = 'auth/login';
        $method = "POST";
        $postField = [
            'username' => $this->_user,
            'password' => $this->_password,
        ];
        $headers =  [
            "cache-control: no-cache",
            "content-type: application/json",
        ];

        $result =  $this->call_cURL($url, $method, $headers, $postField);

        if ($result['status'] == 200) {
            $arrData = json_decode($result['data'], true);
            return $arrData['token'];
        }
        return false;
    }

    public function call_cURL($url, $method, $headers, $postField = false)
    {
        $urlCall = $this->_url_api . $url;
        $curl = curl_init();
        $args = array(
            CURLOPT_URL => $urlCall,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => ($headers),
        );
        if ($postField) {
            $args[CURLOPT_POSTFIELDS] = json_encode($postField);
        }
        curl_setopt_array($curl, $args);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ['status' => 500, 'data' => "cURL Error #:" . $err]; // ;
        } else {
            return ['status' => 200, 'data' => $response];
        }
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
    public static function slugify($str, string $divider = ' ')
    {

        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', ' ', $str);
        $str = preg_replace('/-/', ' ', $str);
        // $str = preg_replace('/quan /', '', $str);
        // $str = preg_replace('/huyen /', '', $str);
        // $str = trim($str);
        return $str;
    }
    function change_name($nameOld)
    {
        if ($nameOld == 'tp thu duc') {
            return "quan thu duc";
        }
        return $nameOld;
    }
}
