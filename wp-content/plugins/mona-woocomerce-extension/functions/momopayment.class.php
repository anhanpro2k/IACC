<?php

function mona_momo_payment()
{
    if (!class_exists('WC_Payment_Gateway'))
        return;

    class mona_momo_payment_gateway extends WC_Payment_Gateway
    {

        public static $log_enabled = false;
        private $test_url = 'https://test-payment.momo.vn';
        private $live_url = 'https://payment.momo.vn';
        private $url = false;
        private $unit = 1;
        public static $log = false;

        function __construct()
        {

            $this->id = 'mona_momo_payment_gateway';
            $this->has_fields = false;
            $this->order_button_text = __('Thanh toán qua ví MOMO', 'monamedia');
            $this->method_title = __('Thanh toán MOMO', 'monamedia');
            $this->method_description = __('', 'monamedia');
            $this->supports = array(
                'products',
            );

            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            // Define user set variables.
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->icon = $this->settings['icon'];
            $this->testmode = $this->get_option('testmode', 'no');
            // if ($this->get_option('testmode', 'no')) {
            //     $this->testmode = 'yes';
            // } else {
            //     $this->testmode = 'no';
            // }
            $this->merchant_id = $this->get_option('merchant_id');
            // $this->storeId = $this->get_option('storeId');
            $this->access_code = $this->get_option('access_code');
            $this->secure_secret = $this->get_option('secure_secret');
            $this->user = $this->get_option('user');
            $this->password = $this->get_option('password');
            $this->unit = max(1, $this->get_option('dolla_unit'));
            $this->debug = 'yes' === $this->get_option('debug', 'no');

            if ($this->testmode == 'no') {
                $this->url = $this->test_url;
            } else {
                $this->url = $this->live_url;
            }
            self::$log_enabled = $this->debug;

            // Process the admin options
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(
                $this,
                'process_admin_options'
            ));

            // add_action('woocommerce_thankyou_' . $this->id, array($this, 'handle_return_url'));

            add_action('woocommerce_api_' . strtolower(__CLASS__), array($this, 'handle_return_url'));
        }

        public static function log($message)
        {
            $log = new WC_Logger();
            $log->add('momo', $message);
        }

        public function init_form_fields()
        {
            // Admin fields
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Activate', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('', 'woocommerce'),
                    'default' => 'no',
                    'value' => 'yes'
                ),
                'testmode' => array(
                    'title' => __('Live', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('', 'woocommerce'),
                    'default' => 'no'
                ),
                'title' => array(
                    'title' => __('Name', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Tên phương thức thanh toán ( khi khách hàng chọn phương thức thanh toán )', 'woocommerce'),
                    'default' => __('MomoVN', 'woocommerce')
                ),
                'icon' => array(
                    'title' => __('Icon', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Icon phương thức thanh toán', 'woocommerce'),
                    'default' => __('https://developers.momo.vn/images/logo.png', 'woocommerce')
                ),
                'description' => array(
                    'title' => __('Mô tả', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('Mô tả phương thức thanh toán.', 'woocommerce'),
                    // 'default' => __('Click place order and you will be directed to the Ngan Luong website in order to make payment', 'woocommerce')
                ),
                'partnerCode' => array(
                    'title' => __('Partner Code', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Thông tin để định danh tài khoản doanh nghiệp.', 'woocommerce'),
                ),
                'accessKey' => array(
                    'title' => __('Access Key', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Cấp quyền truy cập vào hệ thống MoMo.', 'woocommerce'),
                ),
                'dolla_unit' => array(
                    'title' => __('Dolla Unit', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('quy đổi sang tiền việt.', 'woocommerce'),
                ),
                'secretKey' => array(
                    'title' => __('Secret Key', 'woocommerce'),
                    'type' => 'text',
                ),
                'storeId' => array(
                    'title' => __('Store Id', 'woocommerce'),
                    'type' => 'text',
                ),
            );
        }

        /**
         *  There are no payment fields for NganLuongVN, but we want to show the description if set.
         * */
        function payment_fields()
        {
            if ($this->description)
                echo wpautop(wptexturize(__($this->description, 'woocommerce')));
        }

        /**
         * Process the payment and return the result.
         * @param  int $order_id
         * @return array
         */
        public function process_payment($order_id)
        {

            $order = wc_get_order($order_id);
            $checkouturl = $this->generate_Momo_url($order_id);
            // var_dump($checkouturl);
            if ($checkouturl) {
                return array(
                    'result' => 'success',
                    'redirect' => $checkouturl
                );
            }
            $this->log($checkouturl);
            return array(
                'result' => 'error',
                'messenger' => ''
            );
        }

        function generate_Momo_url($order_id)
        {

            $order = wc_get_order($order_id);
            $total = $order->get_total();

            $order_items = $order->get_items();

            $returnUrl = wc()->api_request_url($this->id);
            $partnerCode = $this->settings['partnerCode'];
            $accessKey = $this->settings['accessKey'];
            $secretKey = $this->settings['secretKey'];
            $storeId = $this->settings['storeId'];
            $orderInfo = "Thanh toán đơn hàng từ Lan tỏa yêu thương qua MoMo";
            $amount = $total;
            $orderId = $order_id . '_' . rand();
            $notifyurl = $returnUrl;
            // Lưu ý: link notifyUrl không phải là dạng localhost
            $extraData = ""; // "merchantName=lantoayeuthuong";


            $requestId = time() . "";
            $requestType = "captureWallet";
            $code = [
                'accessKey' => $accessKey,
                'amount' => $amount,
                'extraData' => $extraData,
                'ipnUrl' => $returnUrl,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'partnerCode' => $partnerCode,
                'redirectUrl' => $returnUrl,
                'requestId' => $requestId,
                'requestType' => $requestType,
            ];

            $rawHash = '';
            foreach ($code as $key => $value) {
                $rawHash .= '&' . $key . '=' . $value;
            }
            $rawHash = ltrim($rawHash, '&');

            // exit; 

            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            // $signature2 = hash_hmac("sha256", $rawHash, $secretKey);
            // echo '</pre>';
            // var_dump($code);
            // echo '</pre>';
            // var_dump($signature, $signature2);exit;

            $data = array(
                'partnerCode' => $partnerCode,
                'partnerName' => "", //Lan Tỏa Yêu Thương
                'storeId' => "",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $returnUrl,
                'ipnUrl' => $returnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
            );
            $result = $this->execPostRequest('/v2/gateway/api/create', json_encode($data));

            $jsonResult = json_decode($result, true);
            wc_add_notice($jsonResult['message'], 'error');

            return $jsonResult['payUrl'];
        }

        public function handle_return_url()
        {
            $check = $this->check_handle($_REQUEST);
            wp_redirect($check);
            return;
        }
        protected function check_handle($data_respon)
        {
            if (isset($data_respon['orderId']) && !empty($data_respon['orderId'])) {
                $exp = explode('_', $data_respon['orderId']);
                $order = wc_get_order($exp[0]);


                if (!$order) {
                    return $this->get_return_url();
                }
                $secretKey = $this->settings['secretKey'];
                #
                $partnerCode = $data_respon["partnerCode"];
                // $accessKey = $data_respon["accessKey"];
                $accessKey = $this->settings['accessKey'];
                $orderId = $data_respon["orderId"];
                $localMessage = $data_respon["localMessage"];
                $message = $data_respon["message"];
                $transId = $data_respon["transId"];
                $orderInfo = $data_respon["orderInfo"];
                $amount = $data_respon["amount"];
                // $errorCode = $data_respon["errorCode"];
                $responseTime = $data_respon["responseTime"];
                $requestId = $data_respon["requestId"];
                $extraData = $data_respon["extraData"];
                $payType = $data_respon["payType"];
                $orderType = $data_respon["orderType"];
                $m2signature = $data_respon["signature"]; //MoMo signature 
                $resultCode = $data_respon['resultCode']; //MoMo signature 
                //Checksum  
                $rawHash = "";
                // $code = [
                //     'partnerCode' => $partnerCode,
                //     'accessKey' => $accessKey,
                //     'requestId' => $requestId,
                //     'amount' => $amount,
                //     'orderId' => $orderId,
                //     'orderInfo' => $orderInfo,
                //     'orderType' => $orderType,
                //     'transId' => $transId,
                //     'message' => $message,
                //     'localMessage' => $localMessage,
                //     'responseTime' => $responseTime,
                //     'errorCode' => $errorCode,
                //     'payType' => $payType,
                //     'extraData' => $extraData,
                //     'resultCode' => $data_respon['resultCode'],

                // ];
                $code = [
                    'accessKey' => $accessKey,
                    'amount' => $amount,
                    'extraData' => $extraData,
                    'message' => $message,
                    'orderId' => $orderId,
                    'orderInfo' => $orderInfo,
                    'orderType' => $orderType,
                    'partnerCode' => $partnerCode,
                    'payType' => $payType,
                    'requestId' => $requestId,
                    'responseTime' => $responseTime,
                    'resultCode' => $resultCode,
                    'transId' => $transId,
                ];

                // ksort($code);
                foreach ($code as $key => $value) {
                    $rawHash .= '&' . $key . '=' . $value;
                }
                $rawHash = ltrim($rawHash, '&');
                $partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);
                // var_dump($m2signature, $partnerSignature, "<br>");
                // var_dump($m2signature == $partnerSignature); 
                // var_dump($resultCode);
                // exit;
                if ($m2signature == $partnerSignature) {

                    if ($resultCode == '0') {
                        $order->update_status('on-hold', 'Thanh toán qua momo thành công');
                    } else {
                        $order->update_status('failed', $this->get_error_mess($resultCode));
                    }
                    wc()->cart->empty_cart();
                    return $this->get_return_url($order);
                }
                return $this->get_return_url($order);
            }
            return $this->get_return_url();
        }
        protected function get_error_mess($code)
        {
            $codes = [
                '1' => __('Thiếu thông tin đối tác', 'monamedia'),
                '2' => __('OrderId sai định dạng', 'monamedia'),
                '4' => __('Số tiền thanh toán không hợp lệ.', 'monamedia'),
                '5' => __('Chữ ký không hợp lệ', 'monamedia'),
                '6' => __('Đơn hàng đã tồn tại.', 'monamedia'),
                '7' => __('Giao dịch đang chờ xử lý', 'monamedia'),
                '12' => __('Yêu cầu đã tồn tại', 'monamedia'),
                '14' => __('Đối tác chưa được kích hoạt', 'monamedia'),
                '29' => __('Hệ thống đang bảo trì', 'monamedia'),
                '32' => __('Giao dịch đã được thanh toán', 'monamedia'),
                '33' => __('Giao dịch không thể refund.', 'monamedia'),
                '34' => __('Giao dịch hoàn tiền đã được xử lý', 'monamedia'),
                '36' => __('Giao dịch đã hết hạn', 'monamedia'),
                '37' => __('Tài khoản hết hạn mức giao dịch trong ngày', 'monamedia'),
                '38' => __('Tài khoản khách hàng không đủ tiền', 'monamedia'),
                '42' => __('Yêu cầu không đúng định dạng', 'monamedia'),
                '44' => __('Dịch vụ không hỗ trợ yêu cầu của bạn', 'monamedia'),
                '49' => __('Khách hàng huỷ giao dịch', 'monamedia'),
                '58' => __('Giao dịch không tồn tại', 'monamedia'),
                '59' => __('Yêu cầu không hợp lệ', 'monamedia'),
                '63' => __('Thanh toán bằng nguồn ngân hàng không thành công', 'monamedia'),
                '76' => __('Thiếu field requestType trong HTTP Request Body', 'monamedia'),
                '80' => __('Xác thực khách hàng không thành công', 'monamedia'),
                '99' => __('Lỗi không xác định (Lỗi hệ thống)', 'monamedia'),
                '9043' => __('Khách hàng chưa liên kết tài khoản ngân hàng', 'monamedia'),
            ];
            return @$codes[$code];
        }
        function execPostRequest($method, $data)
        {
            $ch = curl_init($this->url . $method);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data)
                )
            );
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            //execute post
            $result = curl_exec($ch);
            //close connection
            curl_close($ch);
            return $result;
        }
    }

    function mona_paymen_add_momo_gateway($methods)
    {
        $methods[] = 'mona_momo_payment_gateway';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'mona_paymen_add_momo_gateway');
}

// "accessKey=Cpq62MGGcyQin8BS&amount=304000&extraData=&ipnUrl=http://lantoayeuthuong.monamedia.net/wc-api/mona_momo_payment_gateway/&orderId=2187_282419082&orderInfo=Thanh toán đơn hàng từ Lan tỏa yêu thương qua MoMo&partnerCode=MOMOPVPG20210430&redirectUrl=http://lantoayeuthuong.monamedia.net/wc-api/mona_momo_payment_gateway/&requestId=1632421507&requestType=captureWallet"
// array(10) {
//     ["accessKey"]=>
//     string(16) "Cpq62MGGcyQin8BS"
//     ["amount"]=>
//     string(6) "342000"
//     ["extraData"]=>
//     string(0) ""
//     ["ipnUrl"]=>
//     string(70) "http://lantoayeuthuong.monamedia.net/wc-api/mona_momo_payment_gateway/"
//     ["orderId"]=>
//     string(14) "2189_358190371"
//     ["orderInfo"]=>
//     string(61) "Thanh toán đơn hàng từ Lan tỏa yêu thương qua MoMo"
//     ["partnerCode"]=>
//     string(16) "MOMOPVPG20210430"
//     ["redirectUrl"]=>
//     string(70) "http://lantoayeuthuong.monamedia.net/wc-api/mona_momo_payment_gateway/"
//     ["requestId"]=>
//     string(10) "1632848617"
//     ["requestType"]=>
//     string(13) "captureWallet"
//   }