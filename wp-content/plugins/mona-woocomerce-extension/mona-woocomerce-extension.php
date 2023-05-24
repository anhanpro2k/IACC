<?php

/**
 * Plugin Name:Mona Woocommerce Extension
 * Plugin URI: #
 * Description: This plugin provides features and integrations specifically for Vietnam.
 * Author: htdat
 * Author URI: https://mona-media.com
 * Text Domain: monamedia
 * Domain Path: monamedia
 * Version: 1.3.1
 * License:     GPLv2+
 */
if (!defined('ABSPATH')) {
    exit;
}

define('MONA_EXTENSION_PATCH', plugin_dir_path(__FILE__));
define('MONA_EXTENSION_URL', plugins_url('/', __FILE__));
$custom_method = array(
    'giao_hang_tiet_kiem',
    'giao_hang_nhanh',
    'vn_post',
    'viettel_post'
);
define('MONA_SHIPPING_METHOD', json_encode($custom_method));
define('GIAOHANGNHANH_API_LIVE', 'https://online-gateway.ghn.vn/shiip/public-api/');
// define('GIAOHANGNHANH_API_TEST', 'http://api.serverapi.host/api/v1/apiv3/');
define('GIAOHANGNHANH_API_TEST', 'https://dev-online-gateway.ghn.vn/shiip/public-api/');
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    function woothemes_updater_notice()
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
        if (in_array('woothemes-updater/woothemes-updater.php', $active_plugins))
            return;

        $slug = 'woothemes-updater';
        $install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $slug), 'install-plugin_' . $slug);
        $activate_url = 'plugins.php?action=activate&plugin=' . urlencode('woothemes-updater/woothemes-updater.php') . '&plugin_status=all&paged=1&s&_wpnonce=' . urlencode(wp_create_nonce('activate-plugin_woothemes-updater/woothemes-updater.php'));

        $message = '<a href="' . esc_url($install_url) . '">Install the WooThemes Updater plugin</a> to get updates for your WooThemes plugins.';
        $is_downloaded = false;
        $plugins = array_keys(get_plugins());
        foreach ($plugins as $plugin) {
            if (strpos($plugin, 'woothemes-updater.php') !== false) {
                $is_downloaded = true;
                $message = '<a href="' . esc_url(admin_url($activate_url)) . '">Activate the WooThemes Updater plugin</a> to get updates for your WooThemes plugins.';
            }
        }
        echo '<div class="updated fade"><p>' . $message . '</p></div>' . "\n";
    }

    add_action('admin_notices', 'woothemes_updater_notice');
    return;
}

class Mona_extension
{

    private static $instance;

    const VERSION = '1.0.1';

    static $default_settings = array(
        'add_province' =>
        array(
            'enabled' => 'yes',
        ),
        'add_city' =>
        array(
            'enabled' => 'yes',
        ),
        'change_currency_symbol' =>
        array(
            'enabled' => 'yes',
            'text' => 'vnđ',
        ),
        'convert_price' =>
        array(
            'enabled' => 'yes',
            'text' => 'k',
        ),
        'vnd_paypal_standard' =>
        array(
            'enabled' => 'yes',
            'currency' => 'USD',
            'rate' => '22727',
        ),
        'add_onepay_domestic' =>
        array(
            'enabled' => 'yes',
        ),
    );
    protected $Provinces;
    protected $Currency;
    protected $VND_PayPal_Standard;
    protected $Admin_Page;

    public static function get_instance()
    {

        if (null == self::$instance) {
            self::$instance = new Mona_extension();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->i18n();

        if (class_exists('WooCommerce')) {
            // Run this plugin normally if WooCommerce is active
            $this->main();
        } else {
            // Throw a notice if WooCommerce is NOT active
            add_action('admin_notices', array($this, 'notice_if_not_woocommerce'));
        }
    }

    public function load_script()
    {
        wp_enqueue_style('mona--woo-ex-front', MONA_EXTENSION_URL . 'css/style.css');
        if (is_cart() || is_checkout() || is_wc_endpoint_url('edit-address')) {
            wp_enqueue_script('wc-city-select', MONA_EXTENSION_URL . 'js/city-select.js', array('jquery', 'woocommerce'), self::VERSION, true);

            if (!class_exists('WC_City_Select')) {
                include(MONA_EXTENSION_PATCH . 'includes/mona-city-select.class.php');
            }
            $sele = new WC_City_Select();
            $cities = json_encode($sele->get_cities());
            wp_localize_script('wc-city-select', 'wc_city_select_params', array(
                'cities' => $cities,
                'i18n_select_city_text' => esc_attr__('Select an option&hellip;', 'woocommerce')
            ));
        }
    }

    public function admin_script()
    {
        wp_enqueue_style('mona-woo-ex-admin', MONA_EXTENSION_URL . 'css/admin.css');
        wp_enqueue_style('mona-woo-select2-admin', MONA_EXTENSION_URL . 'css/select2.min.css');
        wp_enqueue_script('mona-woo-select2', MONA_EXTENSION_URL . 'js/select2.min.js?a=a', array('jquery'), self::VERSION, true);
        wp_enqueue_script('mona-admin-js', MONA_EXTENSION_URL . 'js/admin.js?a=a', array('jquery'), self::VERSION, true);
    }

    public function notice_if_not_woocommerce()
    {
        $class = 'notice notice-error';

        $message = __('Mona Woocommerce Extension is not running because WooCommerce is not active. Please activate both plugins.', 'monamedia');

        printf('<div class="%1$s"><p><strong>%2$s</strong></p></div>', $class, $message);
    }

    /**
     * Run this method under the "init" action
     */

    /**
     * Localize the plugin
     * @since 1.0
     */
    public function i18n()
    {
        load_plugin_textdomain('monamedia', false, basename(dirname(__FILE__)) . '/languages/');
    }

    /**
     * The main method to load the components
     */
    public function main()
    {
        add_action('wp_enqueue_scripts', array($this, 'load_script'));
        add_action('admin_enqueue_scripts', array($this, 'admin_script'));
        add_filter('woocommerce_checkout_fields', array($this, 'mona_overwrite_billing'), 9999);
        add_filter('woocommerce_locate_template', array($this, 'mona_woocommerce_locate_template'), 10, 3);
        add_filter('woocommerce_get_country_locale', array($this, 'woocommerce_get_country_locale'), 10);
        add_filter('woocommerce_default_address_fields', array($this, 'woocommerce_default_address_fields'), 10);
        if (is_admin()) {
            // Add the admin setting page
            include(MONA_EXTENSION_PATCH . 'includes/mona-admin-page.class.php');
            $this->Admin_Page = new Mona_Admin_Page();

            // Add the notices class
            include(MONA_EXTENSION_PATCH . 'includes/mona-noties.class.php');
            new Mona_Notices();
        }

        $settings = self::get_settings();


        // Check if "Add provinces for Vietnam	" is enabled.
        if ('yes' == $settings['add_province']['enabled']) {
            include(MONA_EXTENSION_PATCH . 'includes/mona-provinces.class.php');
            $this->Provinces = new Mona_Provinces();

            // Enable "Add cities for Vietnam" if the province option is selected.
            if ('yes' == $settings['add_city']['enabled']) {
                include(MONA_EXTENSION_PATCH . 'includes/mona-cities.class.php');
                new Mona_Cities();
            }
        }

        include(MONA_EXTENSION_PATCH . 'includes/mona-currency.class.php');
        $this->Currency = new Mona_Currency();

        // Check if "Change VND currency symbol" is enabled
        if ('yes' == $settings['change_currency_symbol']['enabled']) {
            $this->Currency->change_currency_symbol($settings['change_currency_symbol']['text']);
        }

        // Check if "Convert 000 of prices to K (or anything)" is enabled
        if ('yes' == $settings['convert_price']['enabled']) {
            $this->Currency->convert_price_thousand_to_k($settings['convert_price']['text']);
        }


        // Check if "Support VND for the PayPal Standard gateway" is enabled
        if ('yes' == $settings['vnd_paypal_standard']['enabled']) {
            include(MONA_EXTENSION_PATCH . 'includes/mona-paypal-standard.class.php');
            $this->VND_PayPal_Standard = new Mona_VND_PayPal_Standard(
                $settings['vnd_paypal_standard']['rate'],
                $settings['vnd_paypal_standard']['currency']
            );
        }
    }

    /**
     * The wrapper method to get the settings of the plugin
     * @return array
     */
    static function get_settings()
    {
        $settings = get_option('mona_woo_option', self::$default_settings);
        $settings = wp_parse_args($settings, self::$default_settings);

        return $settings;
    }
    public function woocommerce_get_country_locale($locate)
    {
        $locate['VN'] = array(
            'state'     => array(
                'required' => true,
                'hidden'   => false,
            ),
            'postcode'  => array(
                'priority' => 65,
                'required' => false,
                'hidden'   => false,
            ),
            'address_2' => array(
                'required' => false,
                'hidden'   => false,
            ),
        );
        return $locate;
    }
    public function woocommerce_default_address_fields($fields)
    {
        $fields['address_1'] = array(
            'label'        => __('Phường / Xã', 'woocommerce'),
            /* translators: use local order of street name and house number. */
            'placeholder'  => esc_attr__('Phường / Xã', 'woocommerce'),
            'required'     => true,
            'class'        => array('form-row-wide', 'address-field'),
            'autocomplete' => 'address-line1',
            'priority'     => 50,
        );
        $fields['address_2'] = array(
            'label'        => 'Địa chỉ chi tiết',
            'label_class'  => array('screen-reader-text'),
            'placeholder'  => 'Địa chỉ',
            'class'        => array('form-row-wide', 'address-field'),
            'autocomplete' => 'address-line2',
            'priority'     => 60,
            'required'     => 'required' === get_option('woocommerce_checkout_address_2_field', 'optional'),
        );
        return $fields;
    }


    public function mona_overwrite_billing($fields)
    {
        $priority = 1;
        $fields['billing']['billing_first_name']['priority'] = $priority++;
        $fields['billing']['billing_last_name']['priority'] = $priority++;
        $fields['billing']['billing_email']['priority'] = $priority++;
        $fields['billing']['billing_phone']['priority'] = $priority++;
        $fields['billing']['billing_country']['priority'] = $priority++;
        $fields['billing']['billing_state']['priority'] = $priority++;
        $fields['billing']['billing_city']['priority'] = $priority++;
        $fields['billing']['billing_address_1']['priority'] = $priority++;
        $fields['billing']['billing_address_2']['priority'] = $priority++;

        $fields['billing']['billing_first_name']['label'] = 'Họ tên khách hàng';
        $fields['billing']['billing_address_1']['label'] = 'Phường / Xã';
        $fields['billing']['billing_address_2']['label'] = ' Địa chỉ chi tiết ';
        $fields['billing']['billing_address_2']['placeholder'] = 'Địa chỉ';
        $fields['billing']['billing_address_1']['placeholder'] = 'Phường / Xã';
        // $fields['billing']['billing_address_2']['required'] = true;
        unset($fields['billing']['billing_last_name']);
        foreach ($fields['billing'] as $k => $v) {
            $fields['billing'][$k]['input_class'] = ['form__input rs__form'];
            $fields['billing'][$k]['class'] = ['form__item half'];
            $fields['billing'][$k]['label_class'] = ['form__item'];
        }
        $fields['billing']['billing_first_name']['class'] = ['form__item'];
        //   var_dump($fields['billing']['billing_address_1']);
        return $fields;
    }

    public function mona_woocommerce_locate_template($template, $template_name, $template_path)
    {

        global $woocommerce;

        $_template = $template;

        if (!$template_path)
            $template_path = $woocommerce->template_url;

        $plugin_path = MONA_EXTENSION_PATCH . 'woocommerce/';
        // Look within passed path within the theme - this is priority
        $template = locate_template(
            array(
                $template_path . $template_name,
                $template_name
            )
        );

        // Modification: Get the template from this plugin, if it exists
        if (!$template && file_exists($plugin_path . $template_name))
            $template = $plugin_path . $template_name;

        // Use default template
        if (!$template)
            $template = $_template;

        // Return what we found
        return $template;
    }
}

add_action('plugins_loaded', array('Mona_extension', 'get_instance'), 0);
//add_action('plugins_loaded', 'init_baokim_pro_gateway_class', 1);
//add_action('plugins_loaded', 'mona_gateway_payment_nganluong_init', 2);
//add_action('plugins_loaded', 'mona_gateway_payment_vtcpay_init', 3);
// add_action('plugins_loaded', 'init_Mona_OnePay_Domestic_class', 3);

add_action('plugins_loaded', 'mona_momo_payment', 2);
// add_action('plugins_loaded', 'init_Mona_OnePay_global_Domestic_class', 4);
// define('BAOKIM_API_SELLER_INFO', '/payment/rest/payment_pro_api/get_seller_info');
// define('BAOKIM_API_PAYMENT_PRO', '/payment/rest/payment_pro_api/pay_by_card');

//require (MONA_EXTENSION_PATCH . '/functions/baokim.php');
//require (MONA_EXTENSION_PATCH . '/functions/nganluong.php');
//require (MONA_EXTENSION_PATCH . '/functions/vtcpay.php');

require(MONA_EXTENSION_PATCH . '/functions/momopayment.class.php');
// require (MONA_EXTENSION_PATCH . '/functions/onepay.php');
// require (MONA_EXTENSION_PATCH . '/functions/onepay-global.php');
include(MONA_EXTENSION_PATCH . '/functions/functions.php');
require(MONA_EXTENSION_PATCH . '/includes/mona-shipping-method.class.php');
require(MONA_EXTENSION_PATCH . '/includes/shipping-action.class.php');
require(MONA_EXTENSION_PATCH . '/includes/mona-remote.class.php');
require(MONA_EXTENSION_PATCH . 'functions/baokim/vendor/autoload.php');
function nganluong_return()
{
    $link = get_the_permalink();
    $link = explode('/', $link);
    if (@in_array('nganluong_return', $link)) {
        if (isset($_GET['order_id']) && $_GET['order_id'] != '') {
            mona_payment_ngan_luong_handle($_GET['order_id']);
        }
    }
}

add_action('wp_head', 'nganluong_return', 2);

function mona_payment_ngan_luong_handle($order_id)
{
    $settings = get_option('woocommerce_nganluong_settings', null);
    global $woocommerce;

    // This probably could be written better
    if (isset($_REQUEST['payment_id']) && !empty($_REQUEST['payment_id'])) {
        $settings = get_option('woocommerce_nganluong_settings', null);

        $order_id = $_REQUEST['order_id'];
        $order = new WC_Order($order_id);
        $transaction_info = ''; // urlencode("Order#".$order_id." | ".$_SERVER['SERVER_NAME']);
        $order_code = $_REQUEST['order_code'];
        $price = $_REQUEST['price'];
        $secure_code = $_REQUEST['secure_code'];
        $payment_type = $_REQUEST['payment_type'];
        // This is from the class provided by Ngan Luong. Not advisable to mess.
        // Checks the returned URL from Ngan Luong to see if it matches
        // Tạo mã xác thực từ chủ web
        $str = '';
        $str .= ' ' . strval($transaction_info);
        $str .= ' ' . strval($order_code);
        $str .= ' ' . strval($price);
        $str .= ' ' . strval($_REQUEST['payment_id']);
        $str .= ' ' . strval($payment_type);
        $str .= ' ' . strval($_REQUEST['error_text']);
        $str .= ' ' . strval($settings['merchant_site_code']);
        $str .= ' ' . strval($settings['secure_pass']);

        // Mã hóa các tham số
        $verify_secure_code = '';
        $verify_secure_code = md5($str);

        // Xác thực mã của chủ web với mã trả về từ nganluong.vn
        if ($verify_secure_code === $secure_code) {
            $new_order_status = $settings['status_order'];
            $old_status = 'wc-' . $order->get_status();

            if ($new_order_status !== $old_status) {
                $note = 'Thanh toán trực tuyến qua Ngân Lượng.';
                if ($payment_type == 2) {
                    $note .= ' Với hình thức thanh toán tạm giữ';
                } else if ($payment_type == 1) {
                    $note .= ' Với hình thức thanh toán ngay';
                }
                $note .= ' .Mã thanh toán: ' . $_REQUEST['payment_id'];
                $order->update_status($new_order_status);
                $order->add_order_note(sprintf(__('Cập nhật trạng thái từ %1$s thành %2$s.' . $note, 'woocommerce'), wc_get_order_status_name($old_status), wc_get_order_status_name($new_order_status)), 0, false);
            }

            // Remove cart
            $woocommerce->cart->empty_cart();
            // Empty awaiting payment session
            unset($_SESSION['order_awaiting_payment']);
            wp_redirect(get_permalink($settings['redirect_page_id']));
            exit;
        } else {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><h3>Thông tin giao dịch không chính xác</h3>';
        }
    }
}
add_action('init', 'mona_test_api');
function mona_test_api()
{
    // if (isset($_GET['test-mona'])) {
    //     $token =  viettel_post_get_province();
    //     var_dump($token);
    //     exit;
    // }
}
function viettel_post_get_province()
{
    $token = viettel_post_get_token();
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://transporter.viettelsale.com/v1/location/provinces',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "Authorization:  $token"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return false;
    }
    $response = (array) json_decode($response);
    if ($response['status'] == '200') {
        return $response['data'];
    }
    return false;
}
function viettel_post_get_district_by($provinceId)
{
    $token = viettel_post_get_token();

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://transporter.viettelsale.com/v1/location/districts?provinceId=$provinceId",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return false;
    }
    $response = json_decode($response, true);

    if ($response['status'] == '200') {
        return $response['data'];
    }
    return false;
}
function viettel_post_get_token()
{
    // return false ; 
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://transporter.viettelsale.com/v1/auth/login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "username": "0963795659", 
            "password" : "Luna@2021"
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return false;
    }
    $response = json_decode($response, true);

    return $response['token'];
}
function vn_post_get_province()
{

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://donhang.vnpost.vn/api/api/QuanHuyen/Getall',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Cookie: __Host-SRVNAME=D1'
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return ['status' => 'error', 'data' => "cURL Error #:" . $err];
    } else {
        $data = json_decode($response);
        $result = [];
        if (is_array($data)) {
            foreach ($data  as $key => $value) {
                if (isset($result[$value->MaTinhThanh])) {
                    $result[$value->MaTinhThanh]['District'][$value->MaQuanHuyen] =  $value->TenQuanHuyen;
                } else {

                    $result[$value->MaTinhThanh] = [
                        'NameProvince' => $value->TenTinhThanh,
                        'District' =>  [
                            $value->MaQuanHuyen  => $value->TenQuanHuyen,
                        ],
                    ];
                }
            }
        }
        return ['status' => 'success', 'data' => $result];
    }
}
