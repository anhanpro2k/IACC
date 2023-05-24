<?php
if (!defined('ABSPATH')) {
    exit;
}

class Mona_Admin_Page
{

    /**
     * @var string The message to display after saving settings
     */
    var $message = '';

    /**
     * Mona_Admin_Page constructor.
     */
    public function __construct()
    {
        // Catch and run the save_settings() action
        if (isset($_REQUEST['mona_nonce']) && isset($_REQUEST['action']) && 'mona_save_settings' == $_REQUEST['action']) {
            $this->save_settings();
        }

        add_action('admin_menu', array($this, 'register_submenu_page'));
    }

    /**
     * Save settings for the plugin
     */
    public function save_settings()
    {

        if (wp_verify_nonce($_POST['mona_nonce'], 'mona_save_settings')) {
            $posts = $_POST['settings'];
            $setting = array(
                'change_currency_symbol' => array(
                    'enabled' => (isset($posts['change_currency_symbol']['enabled']) ? $posts['change_currency_symbol']['enabled'] : 'no'),
                    'text' => @$posts['change_currency_symbol']['text'],
                ),
                'convert_price' => array(
                    'enabled' => (isset($posts['convert_price']['enabled']) ? $posts['convert_price']['enabled'] : 'no'),
                    'text' => @$posts['convert_price']['text'],
                ),
                'vnd_paypal_standard' => array(
                    'enabled' => (isset($posts['vnd_paypal_standard']['enabled']) ? $posts['vnd_paypal_standard']['enabled'] : 'no'),
                    'currency' => @$posts['vnd_paypal_standard']['currency'],
                    'rate' => @$posts['vnd_paypal_standard']['rate'],
                ),
                'add_onepay_domestic' => array(
                    'enabled' => 'yes',
                ),
                'add_province' => array(
                    'enabled' => 'yes',
                ),
                'add_city' => array(
                    'enabled' => 'yes',
                ),
            );
            update_option('mona_woo_option', $setting);

            $this->message = '<div class="updated notice"><p><strong>' .
                __('Lưu thành công', 'monamedia') .
                '</p></strong></div>';
        } else {

            $this->message = '<div class="error notice"><p><strong>' .
                __('Không thể lưu thiết lập', 'monamedia') .
                '</p></strong></div>';
        }
    }

    public function register_submenu_page()
    {
        add_menu_page(
            __('Mona Woo Setting', 'monamedia'),
            __('Mona Woo Setting', 'monamedia'),
            'manage_options',
            'mona-woo-setting',
            array(
                $this,
                'admin_page_html'
            ),
            'https://mona-media.com/template/images/logo-top-header.png'
        );
        add_submenu_page(
            'mona-woo-setting',
            __('Giao Hàng Tiết Kiệm API', 'monamedia'),
            'Giao Hàng Tiết Kiệm',
            'manage_options',
            'giao-hang-tiet-kiem-option',
            array($this, 'giao_hang_tiet_kiem_html')
        );
        add_submenu_page(
            'mona-woo-setting',
            __('Giao Hàng Nhanh API', 'monamedia'),
            'Giao Hàng Nhanh',
            'manage_options',
            'giao-hang-nhanh-option',
            array($this, 'giao_hang_nhanh_html')
        );
        add_submenu_page(
            'mona-woo-setting',
            __('VNPost: Bưu điện Việt Nam', 'monamedia'),
            'VNPost: Bưu điện Việt Nam',
            'manage_options',
            'vn-post-option',
            array($this, 'vn_post_html')
        );
        add_submenu_page(
            'mona-woo-setting',
            __('Viettel Post', 'monamedia'),
            'Viettel Post',
            'manage_options',
            'viettel-post-option',
            array($this, 'viettel_post_html')
        );
        add_action('admin_init', array($this, 'mona_add_setting'));
    }

    public function mona_add_setting()
    {
        register_setting('mona_api_ghtk_setting', 'giao_hang_tiet_kiem_active');
        register_setting('mona_api_ghtk_setting', 'giao_hang_tiet_kiem_send_order');
        register_setting('mona_api_ghtk_setting', 'giao_hang_tiet_kiem_api');
        register_setting('mona_api_ghtk_setting', 'ghtk_pick_province');
        register_setting('mona_api_ghtk_setting', 'ghtk_pick_district');
        register_setting('mona_api_ghtk_setting', 'ghtk_pick_ward');
        register_setting('mona_api_ghtk_setting', 'ghtk_pick_street');
        register_setting('mona_api_ghtk_setting', 'ghtk_pick_address');
        register_setting('mona_api_ghtk_setting', 'ghtk_pick_tel');
        register_setting('mona_api_ghtk_setting', 'ghtk_pick_name');
        register_setting('mona_api_ghtk_setting', 'ghtk_pick_email');

        register_setting('mona_api_ghn_setting', 'giao_hang_nhanh_active');
        register_setting('mona_api_ghn_setting', 'giao_hang_nhanh_send_order');
        register_setting('mona_api_ghn_setting', 'giao_hang_nhanh_api');
        register_setting('mona_api_ghn_setting', 'giao_hang_nhanh_tester');
        register_setting('mona_api_ghn_setting', 'ghn_pick_district');
        register_setting('mona_api_ghn_setting', 'ghn_pick_address');
        register_setting('mona_api_ghn_setting', 'ghn_service_id');
        register_setting('mona_api_ghn_setting', 'ghn_contact_name');
        register_setting('mona_api_ghn_setting', 'ghn_contact_phone');
        register_setting('mona_api_ghn_setting', 'ghn_id_shop');

        register_setting('mona_api_vn_post_setting', 'm_vn_post_active');
        register_setting('mona_api_vn_post_setting', 'm_vn_post_user_name');
        register_setting('mona_api_vn_post_setting', 'm_vn_post_user_password');
        register_setting('mona_api_vn_post_setting', 'm_vn_post_products');
        register_setting('mona_api_vn_post_setting', 'm_vn_post_provinces_user');
        register_setting('mona_api_vn_post_setting', 'm_vn_post_district_user');

        register_setting('mona_api_viettel_post_setting', 'm_viettel_post_active');
        register_setting('mona_api_viettel_post_setting', 'm_viettel_post_user_name');
        register_setting('mona_api_viettel_post_setting', 'm_viettel_post_user_password');
        register_setting('mona_api_viettel_post_setting', 'm_viettel_post_products');
        register_setting('mona_api_viettel_post_setting', 'm_viettel_post_provinces_user');
        register_setting('mona_api_viettel_post_setting', 'm_viettel_post_district_user');
    }

    public function admin_page_html()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        echo ' <div class="wrap">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
        $this->mona_admin_page_nav();
        include MONA_EXTENSION_PATCH . 'admin-page/main-setting.php';
        echo '</div>';
    }

    public function giao_hang_tiet_kiem_html()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        echo ' <div class="wrap">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
        $this->mona_admin_page_nav();
        include MONA_EXTENSION_PATCH . 'admin-page/giao-hang-tiet-kiem.php';
        echo '</div>';
    }
    public function giao_hang_nhanh_html()
    {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        echo ' <div class="wrap">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
        $this->mona_admin_page_nav();
        include MONA_EXTENSION_PATCH . 'admin-page/giao-hang-nhanh.php';
        echo ' </div>';
    }

    protected function mona_admin_page_nav()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        echo '<nav class="nav-tab-wrapper mona-nav-tab-wrapp">';
        echo '<a href="admin.php?page=mona-woo-setting" class="nav-tab ' . (@$_GET['page'] == 'mona-woo-setting' ? 'nav-tab-active' : '') . '">Mona Woo Setting</a>';
        echo '</nav>';
    }
    public function vn_post_html()
    {
        echo ' <div class="wrap">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
        $this->mona_admin_page_nav();
        include MONA_EXTENSION_PATCH . 'admin-page/vn-post.php';
        echo ' </div>';
    }
    public function viettel_post_html()
    {
        echo ' <div class="wrap">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';
        $this->mona_admin_page_nav();
        include MONA_EXTENSION_PATCH . 'admin-page/viettel-post.php';
        echo ' </div>';
    }
}
