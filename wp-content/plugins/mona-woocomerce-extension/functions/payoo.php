<?php

function init_Mona_Payment_Payoo_class() {

    class Mona_Payment_Payoo extends WC_Payment_Gateway {

        public function __construct() {
            
            $this->id = 'mona_payoo';
            $this->_NotifyUrl = site_url();
            $this->has_fields = false;
            $this->order_button_text = __('Thanh Toán Payoo', 'monamedia');
            $this->method_title = __('Thanh Toán Payoo (by Monamedia)', 'monamedia');
            $this->method_description = __('Thanh Toán Payoo.', 'monamedia');


            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables.
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');

            $this->_PayooPaymentAPI = $this->get_option('_PayooPaymentAPI');
            $this->username_account = $this->get_option('username_account');
            $this->shop_id = $this->get_option('shop_id');
            $this->shop_title = $this->get_option('shop_title');
            $this->shop_domain = $this->get_option('shop_domain');
            $this->checksum_key = $this->get_option('checksum_key');
            $this->api_username = $this->get_option('api_username');
            $this->api_password = $this->get_option('api_password');
            $this->signature = $this->get_option('signature');
            $this->_PayooPaymentUrl = $this->get_option('payoo_payment_url');

            // self::$log_enabled = $this->debug;
            // Process the admin options
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(
                $this,
                'process_admin_options'
            ));

            add_action('woocommerce_api_mona_payment_payoo', array($this, 'handle_Payoo_return_url'));

            // add_action('woocommerce_api_' . strtolower(__CLASS__), array($this, 'handle_Payoo_ipn'));
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'monamedia'),
                    'type' => 'checkbox',
                    'label' => __('Payoo Gateway (by Mona Media)', 'monamedia'),
                    'default' => 'no'
                ),
                'test_mode' => array(
                    'title' => __('Payoo Sandbox', 'monamedia'),
                    'type' => 'checkbox',
                    'label' => __('Enable Payoo sandbox (testing)', 'woocommerce'),
                    'default' => 'no',
                    'description' => 'Payoo sandbox can be used to test payments. ',
                ),
                'title' => array(
                    'title' => __('Title', 'monamedia'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'monamedia'),
                    'default' => __('Payoo Gateway', 'monamedia'),
                    'desc_tip' => true,
                ),
                'description' => array(
                    'title' => __('Description', 'monamedia'),
                    'type' => 'textarea',
                    'desc_tip' => true,
                    'description' => __('This controls the description which the user sees during checkout.', 'monamedia'),
                    'default' => __(' ', 'monamedia')
                ),
                'username_account' => array(
                    'title' => __('Username/Account', 'monamedia'),
                    'type' => 'text',
                    'description' => '',
                ),
                'shop_id' => array(
                    'title' => __('Shop ID', 'monamedia'),
                    'type' => 'text',
                    'description' => __('Get your Shop ID form Payoo.', 'monamedia'),
                    'default' => '',
                    'desc_tip' => true,
                    'placeholder' => __('Required. Provided by Payoo.', 'monamedia')
                ),
                'shop_title' => array(
                    'title' => __('Shop Title', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Get your Shop Title from Payoo.', 'monamedia'),
                    'default' => '',
                    'desc_tip' => true,
                    'placeholder' => __('Required. Provided by Payoo.', 'monamedia')
                ),
                'shop_domain' => array(
                    'title' => __('Shop Domain', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Get your Shop Domain from Payoo.', 'monamedia'),
                    'default' => '',
                    'desc_tip' => true,
                    'placeholder' => __('Required. Provided by Payoo.', 'monamedia')
                ),
                'payoo_payment_url' => array(
                    'title' => __('Payoo Payment Url', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Get your Payoo Payment Url from Payoo.', 'monamedia'),
                    'default' => '',
                    'desc_tip' => true,
                    'placeholder' => __('Required. Provided by Payoo.', 'monamedia')
                ),
                'checksum_key' => array(
                    'title' => __('Checksum Key', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Get your Checksum Key from Payoo.', 'monamedia'),
                    'default' => '',
                    'desc_tip' => true,
                    'placeholder' => __('Required. Provided by Payoo.', 'monamedia')
                ),
                'api_username' => array(
                    'title' => __('API Username', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Get your user info from Payoo.', 'monamedia'),
                    'default' => '',
                    'desc_tip' => true,
                    'placeholder' => __('Required. Provided by Payoo', 'monamedia')
                ),
                'api_password' => array(
                    'title' => __('API Password', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Get your password info from Payoo.', 'monamedia'),
                    'default' => '',
                    'desc_tip' => true,
                    'placeholder' => __('Required. Provided by Payoo.', 'monamedia')
                ),
                'signature' => array(
                    'title' => __('Signature', 'monamedia'),
                    'type' => 'text',
                    'description' => __('Get your password info from Payoo.', 'monamedia'),
                    'default' => '',
                    'desc_tip' => true,
                    'placeholder' => __('Required. Provided by Payoo.', 'monamedia')
                ),
                '_PayooPaymentAPI' => array(
                    'title' => __('Payoo Payment API', 'monamedia'),
                    'type' => 'text',
                    'description' => __('Get your Url Payment API from Payoo.', 'monamedia'),
                    'default' => '',
                    'desc_tip' => true,
                    'placeholder' => __('Required. Provided by Payoo.', 'monamedia')
                ),
            );
        }

        public function process_payment($order_id) {
            $order = wc_get_order($order_id);

            $a = $this->create_pay_url($order);
            // var_dump($a);
            // die();
            return array(
                'result' => 'success',
                'redirect' => $this->create_pay_url($order),
            );
        }

        public function create_pay_url($order) {

            $shop_back_url = str_replace('https:', 'https:', add_query_arg('wc-api', 'Mona_Payment_Payoo', home_url('/')));


            $Cus_Name = $order->get_formatted_billing_full_name();
            $Cus_Phone = $order->get_billing_phone();
            $Cus_Address = $order->get_formatted_billing_address();
            $Cus_City = (int) $order->get_billing_postcode();
            $Cus_Email = $order->get_billing_email();

            //order 
            $order_no = $order->get_id();
            $order_cash_amount = $order->get_total();
            $order_ship_date = date('d/m/Y'); //
            $order_ship_days = 1;
            $tmp = '';
            $items = $order->get_items();
            foreach ($items as $item) {
                $tmp .= $item->get_name() . 'x' . $item->get_quantity() . ', ';
            }
            $order_detail = "Order No: [" . $order_no . "].</br>Product info:{$tmp}</br>Money total: " . $order_cash_amount;
            $validity_time = date('YmdHis', strtotime('+5 day', time()));

            // Order xml
            $str = ' <shops>
                    <shop>
                        <session>' . $order_no . strtotime('now') . '</session>
                        <username>' . $this->username_account . '</username>
                        <shop_id>' . $this->shop_id . '</shop_id>
                        <shop_title>' . $this->shop_title . '</shop_title>
                        <shop_domain>' . $this->shop_domain . '</shop_domain>
                        <shop_back_url>' . $shop_back_url . '</shop_back_url>
                        <order_no>' . $order_no . '</order_no>
                        <order_cash_amount>' . $order_cash_amount . '</order_cash_amount>
                        <order_ship_date>' . $order_ship_date . '</order_ship_date>
                        <order_ship_days>' . $order_ship_days . '</order_ship_days>
                        <order_description>' . urlencode($order_detail) . '</order_description>
                        <validity_time>' . $validity_time . '</validity_time>
                        <notify_url>' . $shop_back_url . '</notify_url>
                        <customer>
                            <name>' . $Cus_Name . '</name>
                            <phone>' . $Cus_Phone . '</phone>
                            <address>' . $Cus_Address . '</address>
                            <city>' . $Cus_City . '</city>
                            <email>' . $Cus_Email . '</email>
                        </customer>
                    </shop>
                </shops>';

            // var_dump($this->checksum_key.$str);
            $checksum = hash('sha512', $this->checksum_key . $str);

            $check_out_url = $this->_PayooPaymentAPI;

            $ch = curl_init();
            $data = array('data' => $str, 'checksum' => $checksum, 'refer' => $this->shop_domain);

            curl_setopt_array($ch, array(
                CURLOPT_URL => $check_out_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data,
                    )
            );
            $response = curl_exec($ch);

            $err = curl_error($ch);

            curl_close($ch);
            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                $array_result = (array) json_decode($response);
                // var_dump($array_result['order']->payment_url);
                return $array_result['order']->payment_url;
            }
        }

        public function handle_Payoo_return_url() {
			 
			
            if (isset($_POST['NotifyData']) && $_POST['NotifyData'] != '') {
                $this->send_noity_back($_POST['NotifyData']);
            } else {

                $order_id = '';
                if (@$_GET['order_no'] != '') {

                    $order_id = @$_GET['order_no'];
                }
                if ($order_id == '') {
                    wp_redirect(get_home_url());
                    return;
                }
                $data_handle = $_REQUEST;
                $order = new WC_Order($order_id);
                $str_hash = hash('sha512', $this->checksum_key . @$data_handle['session'] . '.' . @$data_handle['order_no'] . '.' . @$data_handle['status']);
				 $order->update_status('completed', json_encode($data_handle));
                if (strtoupper($str_hash) == strtoupper(@$data_handle['checksum'])) {
                    //status = 1 : paid the order 
                    if ($data_handle['status'] == 1) { // The order paid successful  
                        $order->update_status('on-hold', 'giao dịch thành công');
                        update_post_meta($order_id, '_mona_payoo_section', $data_handle['session']);


                        // wp_redirect($this->get_return_url($order));
                    } else { // Payment is Failed Or Cancelled the order
                        $order->update_status('failed', 'thanh toán không thành công');
                    }
                } else { // Verified is faillure
                }
                wp_redirect($order->get_checkout_order_received_url());
            }
        }

        public function send_noity_back($data) {
            $NotifyMessage = stripcslashes($data);
            $content = $this->Parse($NotifyMessage);
            $dt = $this->Parse(base64_decode($content->Data));
            
            $status =get_object_vars($dt->State)[0];
            $order_no = get_object_vars(@$dt->shops->shop->order_no)[0];
            $section =  get_object_vars(@$dt->shops->shop->session)[0];
            $data_respon =  get_object_vars(@$content->Data)[0];
           
            if ($dt == null || '' === $dt || $order_no == '') {
               echo 'Xác thực không thành công!';
                die;
            }
            $order = wc_get_order($order_no);
            if (!$order) {
                echo 'Không tồn tại dơn hàng';
                die;
            }
            
            

            if ($status == 'PAYMENT_RECEIVED') {
                $order->update_status('completed', 'Thanh toán thành công');
               //  echo 'hoàn tất đơn hàng <br>';
            } else {
                $order->update_status('failed', 'Giao dịch bị hủy');
              //  echo 'Hủy đơn hàng <br>';
            }
            echo 'NOTIFY_RECEIVED';
            exit();
        }

        public function Parse($fileContents) {

            $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
            $fileContents = trim(str_replace('"', "'", $fileContents));

            $simpleXml = @simplexml_load_string($fileContents);
            $json = ($simpleXml);

            return $json;
        }

    }

    function add_payoo_gateway_class($methods) {
        if(!is_user_logged_in() || @get_current_user_id()!='22'){
             //return   $methods;
            }
        $methods[] = 'Mona_Payment_Payoo';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_payoo_gateway_class');
}
