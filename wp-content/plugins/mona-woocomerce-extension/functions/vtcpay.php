<?php

function mona_gateway_payment_vtcpay_init() {


    class WC_Gateway_VTCPay extends WC_Payment_Gateway {

        var $notify_url;

        /**
         * Constructor for the gateway.
         *
         * @access public
         * @return \WC_Gateway_VTCPay
         */
        public function __construct() {
            global $woocommerce;

            $this->id = 'vtcpay';
            $this->has_fields = false;
            $this->method_title = __('VTCPAY', 'woocommerce');
            $this->liveurl = 'https://pay.vtc.vn/cong-thanh-toan/checkout.html';
            $this->testurl = 'http://sandbox1.vtcebank.vn/pay.vtc.vn/cong-thanh-toan/checkout.html';

            //load the setting
            $this->init_form_fields();
            $this->init_settings();

            //Define user set variables
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->receiver_acc = $this->get_option('receiver_acc');
            $this->merchant_id = $this->get_option('merchant_id');
            $this->secure_pass = $this->get_option('secure_pass');
            $this->testmode = $this->get_option('testmode');


            $this->form_submission_method = false;

            //Action
            add_action('valid-vtcpay-standard-ipn-request', array($this, 'successful_request'));
            add_action('woocommerce_receipt_vtcpay', array($this, 'receipt_page'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_api_wc_gateway_vtcpay', array($this, 'callback'));
            if (!$this->is_valid_for_use())
                $this->enabled = false;
        }

        function is_valid_for_use() {
            if (!in_array(get_woocommerce_currency(), apply_filters('woocommerce_vtcpay_supported_currencies', array('VND', 'VNĐ', 'USD'))))
                return false;
            return true;
        }

        public function admin_options() {
            ?>
            <h3><?php _e('Thanh toán VTCPay', 'woocommerce'); ?></h3>
            <strong><?php _e('VTCPay giá trị thanh toán đích thực.', 'woocommerce'); ?></strong>
            <?php if ($this->is_valid_for_use()) : ?>

                <table class="form-table">
                <?php
                // Generate the HTML For the settings form.
                $this->generate_settings_html();
                ?>
                </table><!--/.form-table-->

                <?php else : ?>
                <div class="inline error"><p>
                        <strong><?php _e('Gateway Disabled', 'woocommerce'); ?></strong>: <?php _e('Phương thức thanh toán vtcpay không hỗ trợ loại tiền tệ trên gian hàng của bạn.', 'woocommerce'); ?>
                    </p></div>
            <?php
            endif;
        }

        /**
         * Initialise Gateway Settings Form Fields
         *
         * @access public
         * @return void
         */
        function init_form_fields() {

            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Sử dụng phương thức', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Đồng ý', 'woocommerce'),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __('Tiêu đề', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Tiêu đề của phương thức thanh toán bạn muốn hiển thị cho người dùng.', 'woocommerce'),
                    'default' => __('VTCPay', 'woocommerce'),
                    'desc_tip' => true,
                ),
                'description' => array(
                    'title' => __('Mô tả phương thức thanh toán', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('Mô tả của phương thức thanh toán bạn muốn hiển thị cho người dùng.', 'woocommerce'),
                    'default' => __('Thanh toán với VTCPay. Đảm bảo an toàn tuyệt đối cho mọi giao dịch', 'woocommerce')
                ),
                'account_config' => array(
                    'title' => __('Cấu hình tài khoản', 'woocommerce'),
                    'type' => 'title',
                    'description' => 'URL Return : '. strtolower(get_bloginfo('wpurl') . "/?wc-api=WC_Gateway_VTCPay"),
                ),
                'receiver_acc' => array(
                    'title' => __('Số điện thoại đăng kí với VTC', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Số điện thoại đăng kí với VTC', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'merchant_id' => array(
                    'title' => __('website id', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('“Mã website” được VTCPay cấp khi bạn đăng ký tích hợp website.', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'secure_pass' => array(
                    'title' => __('Mã bảo mật', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Mã bảo mật khi bạn đăng ký tích hợp website.', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'testmode' => array(
                    'title' => __('Testmode', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Sử dụng VTCPay kiểm thử', 'woocommerce'),
                    'default' => 'yes',
                    'description' => 'VTCPay kiểm thử được sử đụng kiểm tra phương thức thanh toán.',
                ),
            );
        }

        /**
         * Process the payment and return the result
         *
         * @access public
         * @param int $order_id
         * @return array
         */
        function process_payment($order_id) {
            $order = wc_get_order($order_id);
            if (!$this->form_submission_method) {
                $vtcpay_args = $this->get_vtcpay_args($order);
                if ($this->testmode == 'yes'):
                    $vtcpay_server = $this->testurl;
                else :
                    $vtcpay_server = $this->liveurl;
                endif;
                $vtcpay_url = $this->createRequestUrl($vtcpay_args, $vtcpay_server);
                return array(
                    'result' => 'success',
                    'redirect' => $vtcpay_url
                );
            } else {
                return array(
                    'result' => 'success',
                    'redirect' => add_query_arg('order', $order->get_id(), add_query_arg('key', $order->get_order_key(), get_permalink(woocommerce_get_page_id('pay'))))
                );
            }
        }

        function get_vtcpay_args($order) {
            global $woocommerce;
            $order_id = $order->get_id();
            $vtcpay_args = array(
                'website_id' => strval($this->merchant_id),
                'order_code' => strval($order_id),
                'receiver_acc' => strval($this->receiver_acc),
                'order_des' => strval($order->get_customer_note()),
                'urlreturn' => strtolower(get_bloginfo('wpurl') . "/?wc-api=WC_Gateway_VTCPay"),
                'customer_first_name' => strval($order->get_billing_first_name() . " " . $order->get_billing_last_name()),
                'customer_email' => strval($order->get_billing_email()),
                'customer_mobile' => strval($order->get_billing_phone()),
                'bill_to_address_line1' => strval($order->get_shipping_address_1()),
            );

            $vtcpay_args['amount'] = $order->get_total();
            if (get_woocommerce_currency() === 'VND' || get_woocommerce_currency() === 'VNĐ') {
                $vtcpay_args['payment_method'] = 1;
            } else {
                $vtcpay_args['payment_method'] = 2;
            }


            return $vtcpay_args;
        }

        /**
         * Điều hướng tác vụ xử lý cập nhật đơn hàng sau thanh toán hoặc nhận BPN từ VTCPay
         */
        function callback() {
            $url = get_bloginfo('wpurl');
            if (isset($_GET['status']) && !empty($_GET['status'])) {

                $amount = @$_GET['amount'];
                $message = @$_GET['message'];
                $payment_type = @$_GET['payment_type'];
                $order_code = @$_GET['reference_number'];
                $status = @$_GET['status'];
                $trans_ref_no = @$_GET['trans_ref_no'];
                $website_id = @$_GET['website_id'];
                $sign = @$_GET['signature'];
            } else {
                $data = @$_POST['data'];
                $str_id = explode("|", $data);
                $amount = $str_id[0];
                $message = $str_id[1];
                $payment_type = $str_id[2];
                $order_code = $str_id[3];
                $status = $str_id[4];
                $trans_ref_no = $str_id[5];
                $website_id = $str_id[6];
                $sign = @$_POST['signature'];
            }


            $order = new WC_Order($order_code);
            $check = $this->verifyPaymentUrlLive($amount, $message, $payment_type, $order_code, $status, $trans_ref_no, $website_id, $sign);
            if ($check == 'false') {
                $comment_status = 'Thực hiện thanh toán không thành công với đơn hàng' . $order_code . 'Sai chữ kí';
                $comment_status = $comment_status . '<br/><a href = "' . $url . '">Quay lại</a>';
            } else {
                if ($status == 1) {
                    $comment_status = ' Thực hiện thanh toán thành công với đơn hàng ' . $order_code . '. Giao dịch hoàn thành.';

                    $order->add_order_note(__($comment_status, 'woocommerce'));
                    $order->payment_complete();
                    $comment_status = $comment_status . '<br/><a href = "' . $url . '">Quay lại</a>';
                    $order_status = 'complete';
                } else if ($status == 7) {
                    $comment_status = ' Thực hiện thanh toán thành công với đơn hàng ' . $order_code;
                    $order->update_status('on-hold', sprintf(__('Payment pending: %s', 'woocommerce'), $comment_status));
                    $order_status = 'pending';
                    $comment_status = $comment_status . '<br/><a href = "' . $url . '">Quay lại</a>';
                } else {
                    $comment_status = 'Thực hiện thanh toán không thành công với đơn hàng' . $order_code;
                    $comment_status = $comment_status . '<br/><a href = "' . $url . '">Quay lại</a>';
                }
            }

            echo $comment_status;
            exit();
        }

        function verifyPaymentUrlLive($amount, $message, $payment_type, $order_code, $status, $trans_ref_no, $website_id, $sign) {

            // My plaintext
            $secret_key = $this->secure_pass;
            $plaintext = $amount . "|" . $message . "|" . $payment_type . "|" . $order_code . "|" . $status . "|" . $trans_ref_no . "|" . $website_id . "|" . $secret_key;
            //print $plaintext;
            // Mã hóa sign
            $verify_secure_code = '';
            $verify_secure_code = strtoupper(hash('sha256', $plaintext));
            ;
            // Xác thực chữ ký của ch? web v?i ch? ký tr? v? t? VTC Pay
            if ($verify_secure_code === $sign)
                return strval($status);

            return false;
        }

        private function createRequestUrl($data, $vtcpay_server) {
            $params = $data;
            $security = $this->secure_pass;
            $plaintext = $params['website_id'] . "-" . $params['payment_method'] . "-" . $params['order_code'] . "-" . $params['amount'] . "-" . $params['receiver_acc'] . "-" . "-" . $security . "-" . $params['urlreturn'];
            $params['sign'] = strtoupper(hash('sha256', $plaintext));

            $params['urlreturn'] = urlencode($params['urlreturn']);

            $redirect_url = $vtcpay_server;
            if (strpos($redirect_url, '?') === false) {
                $redirect_url .= '?';
            } else if (substr($redirect_url, strlen($redirect_url) - 1, 1) != '?' && strpos($redirect_url, '&') === false) {
                $redirect_url .= '&';
            }

            // Tạo đoạn url chứa tham số
            $url_params = '';
            foreach ($params as $key => $value) {
                if ($url_params == '')
                    $url_params .= $key . '=' . ($value);
                else
                    $url_params .= '&' . $key . '=' . ($value);
            }
            return $redirect_url . $url_params;
        }

    }

    class WC_VTCPay extends WC_Gateway_VTCPay {

        public function __construct() {
            _deprecated_function('WC_VTCPay', '1.4', 'WC_Gateway_VTCPay');
            parent::__construct();
        }

    }

    //Defining class gateway
    function add_gateway_class($methods) {
        $methods[] = 'WC_Gateway_VTCPay';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_gateway_class');
}
