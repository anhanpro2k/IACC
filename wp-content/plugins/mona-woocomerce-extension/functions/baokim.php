<?php
require_once(MONA_EXTENSION_PATCH . '/functions/baokim/BaoKimAPI.php');

function init_baokim_pro_gateway_class() {

    class Mona_baokim_payment extends WC_Payment_Gateway {

        var $_Baokim_API;
        var $_serverurl;
        var $_serverredierect;
        var $_testurl = 'https://sandbox-api.baokim.vn';
        var $_liveurl = 'https://api.baokim.vn';
        var $test_server = 'http://sandbox.baokim.vn';
        var $live_server = 'https://baokim.vn';
        var $id = 'baokim_pro';
        var $has_fields = false;
        var $method_title = 'BaoKim Payment (By mona)';

        public function __construct() {
            //load the setting
            $this->init_form_fields();
            $this->init_settings();
            //Define user set variables
            $this->title = $this->get_option('title');
            $this->icon = $this->get_option('icon');
            $this->description = $this->get_option('description');
            $this->email = $this->get_option('email');
            $this->api_key = $this->get_option('bk_api_key');
            $this->api_secret = $this->get_option('bk_api_secret');
            $this->bk_bank_type = $this->get_option('bk_bank_type', 1);
            $this->testmode = $this->get_option('testmode');
            $this->bpn_file = $this->get_option('bpn_file');
            $this->order_button_text = __('Thanh Toán Qua Bảo Kim', 'woocommerce');
            $this->_Baokim_API = new BaoKimAPI($this->api_key, $this->api_secret);
            if (!$this->is_valid_for_use()) {
                $this->enabled = false;
            }


            $this->form_submission_method = false;

            if ($this->testmode=='yes') {
                $this->_serverurl = $this->_testurl;
                $this->_serverredierect = $this->test_server;
            } else {
                $this->_serverurl = $this->_liveurl;
                $this->_serverredierect = $this->live_server;
            }
            if (!is_admin()) {
                $html_plus = $this->inti_credit_card();
                $this->description .= $html_plus;
            }
            //Action
            add_action('valid-baokim-standard-ipn-request', array($this, 'successful_request'));
            add_action('woocommerce_receipt_baokim', array($this, 'receipt_page'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_api_mona_baokim_payment', array($this, 'callback'));
        }

        private function inti_credit_card() {
            $client = new GuzzleHttp\Client(['timeout' => 20.0]);
            $options = [
                'query' => [
                    'type' => $this->bk_bank_type,
                    'jwt' => $this->_Baokim_API->getToken(),
                ]
            ];
            $response = $client->request("GET", $this->_serverurl . "/payment/api/v4/bpm/list", $options);
            if (!$this->is_error($response)) {
               
                $content = json_decode($response->getBody()->getContents(), true);
                 if ($content['code'] > 0) {
                    return '';
                }
                if (isset($content['data']) && is_array($content['data'])) {
                    $html = '<ul class="mona-list-bank">';
                    $check = 'checked';
                    foreach ($content['data'] as $item) {
                        if ($item['bank_short_name'] != 'Bảo Kim') {
                            $html .= '<li class="item"><label><input type="radio" ' . $check . ' name="mona-bank-id" value="' . $item['id'] . '" class="mona-change-bank mona-hiden"/><span class="label"><img src="' . $item['bank_logo'] . '"/><span class="plahonder">' . $item['bank_short_name'] . '</span></span></label></li>';
                            $check = '';
                        }
                    }
                    $html .= '</ul>';
                    return $html;
                }
            }
            return '';
        }

        /**
         * Check if this gateway is enabled and available in the user's country
         *
         * @access public
         * @return bool
         */
        function is_valid_for_use() {
            if (!in_array(get_woocommerce_currency(), apply_filters('woocommerce_baokim_supported_currencies', array('VND', 'VNĐ', 'USD'))))
                return false;
            return true;
        }

        /**
         * Admin Panel Options
         * - Options for bits like 'title' and availability on a country-by-country basis
         *
         * @since 1.0.0
         */
        public function admin_options() {
            $string = get_home_url() . '?wc-api=mona_baokim_payment&action=bpn';
            ?>
            <h3><?php _e('Thanh toán Bảo Kim', 'woocommerce'); ?></h3>
            <strong style="display: none;"><?php _e('Đảm bảo an toàn tuyệt đối cho mọi giao dịch.</br>  Url khai báo trả về:  ' . $string, 'woocommerce'); ?></strong>
            <?php if ($this->is_valid_for_use()) : ?>

                <table class="form-table">
                    <?php
                    // Generate the HTML For the settings form.
                    $this->generate_settings_html();
                    ?>
                </table><!--/.form-table-->

            <?php else : ?>
                <div class="inline error"><p>
                        <strong><?php _e('Gateway Disabled', 'woocommerce'); ?></strong>: <?php _e('Phương thức thanh toán Bảo Kim không hỗ trợ loại tiền tệ trên gian hàng của bạn.', 'woocommerce'); ?>
                    </p></div>
            <?php
            endif;
        }

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
                    'default' => __('Bảo Kim', 'woocommerce'),
                    'desc_tip' => true,
                ),
                'icon' => array(
                    'title' => __('icon', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('', 'woocommerce'),
                    'default' => __('https://www.baokim.vn/assets/images/logo/baokim.png', 'woocommerce'),
                    'desc_tip' => true,
                ),
                'description' => array(
                    'title' => __('Mô tả phương thức thanh toán', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('Mô tả của phương thức thanh toán bạn muốn hiển thị cho người dùng.', 'woocommerce'),
                    'default' => __('Thanh toán với Bảo Kim. Thực hiện thanh toán với thẻ ngân hàng trực tuyến', 'woocommerce')
                ),
                'account_config' => array(
                    'title' => __('Cấu hình tài khoản', 'woocommerce'),
                    'type' => 'title',
                    'description' => '',
                ),
                'email' => array(
                    'title' => __('E-mail Bảo Kim', 'woocommerce'),
                    'type' => 'email',
                    'description' => __('E-mail tài khoản bạn đăng ký với BaoKim.vn.', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                    'placeholder' => 'you@youremail.com'
                ),
                'api_account_config' => array(
                    'title' => __('API', 'woocommerce'),
                    'type' => 'title',
                    'description' => '',
                ),
                'bk_api_key' => array(
                    'title' => __('BaoKim API KEY', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'bk_api_secret' => array(
                    'title' => __('BaoKim API SECRET', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'bk_bank_type' => array(
                    'title' => __('Loại thẻ ngân hàng', 'woocommerce'),
                    'type' => 'select',
                    'description' => __('', 'woocommerce'),
                    'default' => '1',
                    'desc_tip' => true,
                    'options' => array(
                        '0' => __('thanh toán từ ví Bảo Kim'),
                        '1' => __('thẻ ATM online các ngân hàng'),
                        '2' => __(' thẻ visa/master')
                    ),
                ),
                'testmode' => array(
                    'title' => __('Bảo Kim kiểm thử', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Sử dụng Bảo Kim kiểm thử', 'woocommerce'),
                    'default' => 'yes',
                    'description' => 'Bảo Kim kiểm thử được sử đụng kiểm tra phương thức thanh toán.',
                ),
                'testing' => array(
                    'title' => __('Cấu hình BPN(BaoKim Payment Notification)', 'woocommerce'),
                    'type' => 'title',
                    'description' => '',
                ),
                'bpn_file' => array(
                    'title' => __('Tên file lưu log', 'woocommerce'),
                    'type' => 'text',
                    'description' => sprintf(__('Tên file lưu trữ log trong quá trình thực hiện BPN, truy cập file log <code>woocommerce/logs/bpn-%s.log</code>', 'woocommerce'), date("d-m")),
                    'default' => 'bpn',
                    'desc_tip' => true,
                ),
            );
        }

        function process_payment($order_id) {
            $order = new WC_Order($order_id);
            $result = $this->get_baokim_args($order);
            if (!$this->is_error($result)) {
                $content = json_decode($result->getBody()->getContents(), true);
                if ($content['code'] === 0) {

                    return [
                        'result' => 'success',
                        'redirect' => $this->_serverredierect . $content['data']['redirect_url']
                    ];
                }
            }
            return array(
                'result' => 'failure',
                'messages' => 'Không tạo được đơn hàng'
            );
        }

        /**
         * Lấy thông tin đơn hàng
         * @param mixed $order
         * @internal param order_id             Mã đơn hàng
         * @internal param business             Tài khoản người bán
         * @internal param total_amount         Giá trị đơn hàng
         * @internal param shipping_fee         Phí vận chuyển
         * @internal param tax_fee              Thuế
         * @internal param order_description    Mô tả đơn hàng
         * @internal param url_success          Url trả về khi thanh toán thành công
         * @internal param url_cancel           Url trả về khi hủy thanh toán
         * @internal param url_detail           Url chi tiết đơn hàng
         * @internal param payer_name           Thông tin thanh toán
         * @internal param payer_email
         * @internal param payer_phone_no
         * @internal param shipping_address
         * @access public
         * @return array
         */
        function get_baokim_args($order) {

            $client = new GuzzleHttp\Client(['timeout' => 20.0]);
            $options = [
                'query' => [
                    'jwt' => $this->_Baokim_API->getToken(),
                ],
                'form_params' => [
                    'mrc_order_id' => $order->get_id() . '_' . strtotime('now'),
                    'total_amount' => $order->get_total(),
                    'description' => 'thanh toán đơn hàng ' . $order->get_id() . ' qua bảo kim',
                    'url_detail' => $order->get_checkout_payment_url(),
                    'url_success' => add_query_arg(['wc-api' => 'Mona_baokim_payment', 'webhook' => 'no'], get_home_url('/')),
                    'lang' => 'vi',
                    'shipping_fee' => $order->get_shipping_total(),
                    'tax_fee' => $order->get_total_tax(),
                    'bpm_id' => $_POST['mona-bank-id'],
                    //  'customer_email'=>$order->get_billing_email(),
                    //  'customer_phone'=>'',
                    'customer_name' => $order->get_formatted_billing_full_name(),
                    'customer_address' => wp_kses_post($order->get_formatted_billing_address()),
                    'webhooks' => add_query_arg(['wc-api' => 'Mona_baokim_payment', 'webhook' => 'yes'], get_home_url('/'))
                ]
            ];
         
           $response = $client->request("POST", $this->_serverurl . "/payment/api/v4/order/send", $options);
            return $response;
        }

        /**
         * Điều hướng tác vụ xử lý cập nhật đơn hàng sau thanh toán hoặc nhận BPN từ Bảo Kim
         */
        function callback() {
            if (@$_REQUEST['mrc_order_id'] != '' && @$_REQUEST['txn_id'] != '') {
                $exp = explode('_', $_REQUEST['mrc_order_id']);
                $order_id = $exp[0];
                $order = wc_get_order($order_id);
                if ($order) {
                    $status = $order->get_status();
                    if ($status == 'pending'||$status == 'on-hold') {
                        $response = $this->get_orde_detail($_REQUEST['id'], $_REQUEST['mrc_order_id']);
                       
                        if (!$this->is_error($response)) {
                             $response = json_decode($response->getBody()->getContents(), true);
                              if ($response['code'] === 0) {
                                  $status = $response['data']['stat'];
                                if($status=='c'||$status=='p'){
                                    $order->update_status('processing', 'thanh toán thành công');
                                }elseif($status=='d'){
                                    $order->update_status('failed', 'Bảo kim từ chối đơn hàng');
                                }elseif($status=='r'){
                                    $order->update_status('on-hold', 'Chờ xác nhận chuyển tiền');
                                }  
                              }
                            
                           
                        }
                    }
                }
            }
            wp_redirect($order->get_checkout_order_received_url());
            return;
        }

        public function get_orde_detail($id, $orderid) {
            $client = new GuzzleHttp\Client(['timeout' => 20.0]);
            $options = [
                'query' => [
                    'jwt' => $this->_Baokim_API->getToken(),
                    'id' => $id,
                    'mrc_order_id' => $orderid,
                ]
            ];
            $response = $client->request("GET", $this->_serverurl . "/payment/api/v4/order/detail", $options);
            return $response;
        }

        /**
         * Hàm thực hiện kiểm tra đơn hàng và cập nhập trạng thái đơn hàng sau khi thanh toán tại baokim.vn
         */

        /**
         * BAOKIM PAYMENT NOTIFICATION
         */
        private function baokim_payment_notification() {
            
        }

        /**
         * Hàm xây dựng url chuyển đến BaoKim.vn thực hiện thanh toán, trong đó có tham số mã hóa (còn gọi là public key)
         * @param $data             Các tham số thông tin đơn hàng gửi đến BaoKim
         * @param $baokim_server    URL Server xử lý đơn hàng của Bảo Kim.
         * @return url cần tạo


        /**
         * Hàm thực hiện xác minh tính chính xác thông tin trả về từ BaoKim.vn
         * @param array $url_params chứa tham số trả về trên url
         * @return true nếu thông tin là chính xác, false nếu thông tin không chính xác
         */
        

        private function is_error($response) {

            if ($response->getStatusCode() === 200) {
                return false;
            }
            return true;
        }

    }

    //Defining class gateway
    function add_baokim_pro_gateway_class($methods) {
        $methods[] = 'Mona_baokim_payment';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_baokim_pro_gateway_class');
}
