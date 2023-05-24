<?php

class Mona_Shipping_giao_hang_tiet_kiem extends WC_Shipping_Method
{

    /** @var string cost passed to [fee] shortcode */
    protected $fee_cost = '';

    /**
     * Constructor.
     *
     * @param int $instance_id
     */
    public function __construct($instance_id = 0)
    {
        $active = get_option('giao_hang_tiet_kiem_active');
        if ($active != 'yes') {
            return;
        }

        $this->id = 'giao_hang_tiet_kiem';
        $this->instance_id = absint($instance_id);
        $this->method_title = __('Giao Hàng Tiết Kiệm', 'monamedia');
        $this->method_description = __('Giao Hàng Qua <a target="_blank" href="https://giaohangtietkiem.vn/">Giao Hàng Tiết Kiệm</a>', 'monamedia');
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
                'default' => __('Giao Hàng Tiết Kiệm', 'monamedia'),
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

    public function render_shipping_price($package)
    {
        $active = get_option('giao_hang_tiet_kiem_active');
        if ($active != 'yes') {
            return 0;
        }
        $token = get_option('giao_hang_tiet_kiem_api');
        if ($token == '') {
            return 0;
        }
        global $woocommerce;
        $items = $woocommerce->cart->get_cart();
        if (is_array($items) && count($items) > 0) {
            $address = WC()->customer->get_shipping_address();
            $city = WC()->customer->get_shipping_city();
            $address = WC()->customer->get_shipping_address();
            $stated = WC()->customer->get_shipping_state();
            $contry = WC()->customer->get_shipping_country();
            if ($contry != 'VN') {
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
            $pick_province = get_option('ghtk_pick_province');
            $pick_district = get_option('ghtk_pick_district');
            $pick_ward = get_option('ghtk_pick_ward');
            $pick_street = get_option('ghtk_pick_street');
            $pick_address = get_option('ghtk_pick_address');

            $data = array(
                "pick_province" => $pick_province,
                "pick_district" => $pick_district,
                "pick_ward" => $pick_ward,
                "pick_street" => $pick_street,
                "pick_address" => $pick_address,
                "province" => $stated,
                "district" => $city,
                "address" => $address,
                "weight" => 0,
                "value" => $package['cart_subtotal'],
            );
            $weight = 0;
            foreach ($items as $cart_item) {
                $quantity = $cart_item['quantity'];
                $product = $cart_item['data'];
                $w = (float) $product->get_weight();
                $w = (float) $w * 1000;
                $weight += (float) $w * (int) $quantity;
            }
            $data['weight'] = $weight;
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://services.giaohangtietkiem.vn/services/shipment/fee?" . http_build_query($data),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HTTPHEADER => array(
                    "Token:" . $token,
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, true);

            if (@$response['success'] == true && @$response['fee']['delivery'] == true) {
                $f = (float) $response['fee']['fee'] + $response['fee']['insurance_fee'];
                return $f;
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
