<?php

class Mona_Shipping_giao_hang_jangin extends WC_Shipping_Method {

    /** @var string cost passed to [fee] shortcode */
    protected $fee_cost = '';
    protected $_token;

    /**
     * Constructor.
     *
     * @param int $instance_id
     */
    public function __construct($instance_id = 0) {
        //  var_dump('cc');
        // $active = get_option('giao_hang_nhanh_active');
        // if ($active != 'yes') {
        //     return;
        // }
        // $this->_token = get_option('giao_hang_nhanh_api');
        $this->id = 'giao_hang_jangin';
        $this->instance_id = absint($instance_id);
        $this->method_title = __('Giao Hàng jangin', 'monamedia');
        $this->method_description = __('_description', 'monamedia');
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
    public function init() {
        $settings = array(
            'title' => array(
                'title' => __('Tên Hiển Thị', 'monamedia'),
                'type' => 'text',
                'description' => __('Tên Hiển Thị', 'monamedia'),
                'default' => __('Giao Hàng ', 'monamedia'),
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
    protected function evaluate_cost($sum, $args = array()) {
        include_once( WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php' );

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
                        ), array(
            $args['qty'],
            $args['cost'],
                        ), $sum
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
    public function fee($atts) {
        $atts = shortcode_atts(array(
            'percent' => '',
            'min_fee' => '',
            'max_fee' => '',
                ), $atts, 'fee');

        $calculated_fee = 0;

        if ($atts['percent']) {
            $calculated_fee = $this->fee_cost * ( floatval($atts['percent']) / 100 );
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
    public function calculate_shipping($package = array()) {
        // var_dump($package);
        // var_dump($this->get_rate_id());
        
        $state = WC()->customer->get_billing_state();
        $city = WC()->customer->get_billing_city();
        $address_1 = WC()->customer->get_billing_address_1();
        
        if ($city == '' || $address_1 == '' || $state == '') {
            return 0;
             
        }
        $cost = $this->render_shipping_price($package);
        if($cost===false){
            $rate = array(
                'id' => $this->get_rate_id(),
                'label' => 'Không giao đến khu vực này nha',
                'cost' =>0 ,//-1,
                // 'check' => 01,
                'package' => $package,
            ); 
            return false;
        }elseif($cost===0){
            $rate = array(
                'id' => $this->get_rate_id(),
                'label' => 'Miễn phí giao hàng',
                'cost' => 0,//-1,
                'taxes' => false,
                'package' => $package,
            );   
        }elseif($cost===0){
            return false;
        }else{
            $rate = array(
                'id' => $this->get_rate_id(),
                'label' => $this->title,
                'cost' => $cost,
            
                'package' => $package,
            );   
        }
        
        $this->add_rate($rate); 
        // if( $rate['cost'] == -1) { 
        //     $rate['cost'] = 0;
        // }
        do_action('woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate);
    }

    public function render_shipping_price($package) {
    
        $total_price = WC()->cart->get_subtotal();

    
        $state = WC()->customer->get_billing_state();
        $city = WC()->customer->get_billing_city();
        $address_1 = WC()->customer->get_billing_address_1();
    
        if ($city == '' || $address_1 == '' || $state == '') {
            return 0;
        }
        $array_result = $this->get_columns_place($state, $city, $address_1);
      
        $xe = @wc()->session->get('xe_vc');
        
        if ($array_result) {
            $key_xe = ( $xe == '1_tan' ? 0 : 1 );
            $price = $array_result['xe'][$key_xe];
            if ($price == 0) {
                return false; //không giao tới
            }
            $key_percent = $this->array_money_filter($total_price);
            $percent = $array_result['array_money'][$key_percent];

            $price = $price * ( $percent / 100 );
            
            if ($price == 0) {
                return 0; //free ship
            }
            return $price;
        }
        
    }

    public function array_money_filter($money) {

        $array_money = array(
            '100000000' => 'below_150',
            '60000000' => 'below_100',
            '40000000' => 'below_60',
            '20000000' => 'below_40',
            '10000000' => 'below_20',
            '0' => 'below_10',
                // '150000000' => 'bigger_150', 
        );
        if ($money >= 150000000)
            return 'bigger_150';
        foreach ($array_money as $key => $item) {
            if ($money >= $key) {
                return $item;
            }
        }
    }

    public function get_columns_place($state, $city, $address_1) {
        global $wpdb;
        $sql = "SELECT * FROM `mona_place` WHERE `address_province` = '{$state}' AND `address_district` = '{$city}' AND `address_wards` = '{$address_1}' ";
        $result = $wpdb->get_results($sql, ARRAY_N);
        $result = @$result[0];
        $array_money = array(
            'below_10' => (int) $result[7],
            'below_20' => (int) $result[8],
            'below_40' => (int) $result[9],
            'below_60' => (int) $result[10],
            'below_100' => (int) $result[11],
            'below_150' => (int) $result[12],
            'bigger_150' => (int) $result[13],
        );
        $xe_1_tan = @$result[5];
        $xe_2_tan = @$result[6];
        $code = $result[1];
        
        return array('array_money' => $array_money, 'xe' => [$xe_1_tan, $xe_2_tan], 'code' => $code);
    }

    /**
     * Get items in package.
     * @param  array $package
     * @return int
     */
    public function get_package_item_qty($package) {
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
    public function find_shipping_classes($package) {
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
