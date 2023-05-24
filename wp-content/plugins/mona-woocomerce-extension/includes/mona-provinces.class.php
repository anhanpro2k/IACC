<?php

if (!defined('ABSPATH')) {
    exit;
}

class Mona_Provinces {

    /**
     * Constructor: Add filters
     */
    public function __construct() {
        add_filter('woocommerce_states', array($this, 'add_provinces'));
        add_filter('woocommerce_get_country_locale', array($this, 'edit_vn_locale'));
        add_filter('woocommerce_localisation_address_formats', array($this, 'edit_vn_address_formats'));
    }

    /**
     * Change the address format of Vietnam, add {state} (or "Province" in Vietnam)
     *
     * @param $array
     *
     * @return array
     */
    public function edit_vn_address_formats($array) {

        $array['VN'] = "{name}\n{address_1}\n{city}\n{state}\n{country}";

        return $array;
    }

    /**
     * Change the way displaying address fields in the checkout page when selecting Vietnam
     *
     * @param $array
     *
     * @return array
     */
    public function edit_vn_locale($array) {
        $array['VN']['state']['label'] = __('Tỉnh / Thành phố', 'woocommerce');
        $array['VN']['state']['required'] = true;
        $array['VN']['city']['label'] = __('Quận / Huyện', 'monamedia');
        $array['VN']['city']['priority'] = 99;
        $array['VN']['address_1']['priority'] = 100;
        $array['VN']['postcode']['hidden'] = true;
        $array['VN']['billing_company']['hidden'] = true;
        return $array;
    }

    public function add_provinces($states) {
        /**
         * @source: https://vi.wikipedia.org/wiki/Tỉnh_thành_Việt_Nam and https://en.wikipedia.org/wiki/Provinces_of_Vietnam
         */
        $states['VN'] = array(
            'AN-GIANG' => __('An Giang', 'monamedia'),
            'BA-RIA-VUNG-TAU' => __('Bà Rịa - Vũng Tàu', 'monamedia'),
            'BAC-LIEU' => __('Bạc Liêu', 'monamedia'),
            'BAC-KAN' => __('Bắc Kạn', 'monamedia'),
            'BAC-GIANG' => __('Bắc Giang', 'monamedia'),
            'BAC-NINH' => __('Bắc Ninh', 'monamedia'),
            'BEN-TRE' => __('Bến Tre', 'monamedia'),
            'BINH-DUONG' => __('Bình Dương', 'monamedia'),
            'BINH-DINH' => __('Bình Định', 'monamedia'),
            'BINH-PHUOC' => __('Bình Phước', 'monamedia'),
            'BINH-THUAN' => __('Bình Thuận', 'monamedia'),
            'CA-MAU' => __('Cà Mau', 'monamedia'),
            'CAO-BANG' => __('Cao Bằng', 'monamedia'),
            'CAN-THO' => __('Cần Thơ', 'monamedia'),
            'DA-NANG' => __('Đà Nẵng', 'monamedia'),
            'DAK-LAK' => __('ĐăkLăk', 'monamedia'),
            'DAK-NONG' => __('DăkNông', 'monamedia'),
            'DONG-NAI' => __('Đồng Nai', 'monamedia'),
            'DONG-THAP' => __('Đồng Tháp', 'monamedia'),
            'DIEN-BIEN' => __('Điện Biên', 'monamedia'),
            'GIA-LAI' => __('Gia Lai', 'monamedia'),
            'HA-GIANG' => __('Hà Giang', 'monamedia'),
            'HA-NAM' => __('Hà Nam', 'monamedia'),
            'HA-NOI' => __('Hà Nội', 'monamedia'),
            'HA-TINH' => __('Hà Tỉnh', 'monamedia'),
            'HAI-DUONG' => __('Hải Dương', 'monamedia'),
            'HAI-PHONG' => __('Hải Phòng', 'monamedia'),
            'HOA-BINH' => __('Hòa Bình', 'monamedia'),
            'HAU-GIANG' => __('Hậu Giang', 'monamedia'),
            'HUNG-YEN' => __('Hưng Yên', 'monamedia'),
            'HO-CHI-MINH' => __('Hồ Chí Minh', 'monamedia'),
            'KHANH-HOA' => __('Khánh Hòa', 'monamedia'),
            'KIEN-GIANG' => __('Kiên Giang', 'monamedia'),
            'KON-TUM' => __('KonTum', 'monamedia'),
            'LAI-CHAU' => __('Lai Châu', 'monamedia'),
            'LAO-CAI' => __('Lào Cai', 'monamedia'),
            'LANG-SON' => __('Lạng Sơn', 'monamedia'),
            'LAM-DONG' => __('Lâm Đồng', 'monamedia'),
            'LONG-AN' => __('Long An', 'monamedia'),
            'NAM-DINH' => __('Nam Định', 'monamedia'),
            'NGHE-AN' => __('Nghệ An', 'monamedia'),
            'NINH-BINH' => __('Ninh Bình', 'monamedia'),
            'NINH-THUAN' => __('Ninh Thuận', 'monamedia'),
            'PHU-THO' => __('Phú Thọ', 'monamedia'),
            'PHU-YEN' => __('Phú Yên', 'monamedia'),
            'QUANG-BINH' => __('Quảng Bình', 'monamedia'),
            'QUANG-NAM' => __('Quảng Nam', 'monamedia'),
            'QUANG-NGAI' => __('Quảng Ngãi', 'monamedia'),
            'QUANG-NINH' => __('Quảng Ninh', 'monamedia'),
            'QUANG-TRI' => __('Quảng Trị', 'monamedia'),
            'SOC-TRANG' => __('Sóc Trăng', 'monamedia'),
            'SON-LA' => __('Sơn La', 'monamedia'),
            'TAY-NINH' => __('Tây Ninh', 'monamedia'),
            'THAI-BINH' => __('Thái Bình', 'monamedia'),
            'THAI-NGUYEN' => __('Thái Nguyên', 'monamedia'),
            'THANH-HOA' => __('Thanh Hóa', 'monamedia'),
            'THUA-THIEN-HUE' => __('Thừa Thiên - Huế', 'monamedia'),
            'TIEN-GIANG' => __('Tiền Giang', 'monamedia'),
            'TRA-VINH' => __('Trà Vinh', 'monamedia'),
            'TUYEN-QUANG' => __('Tuyên Quang', 'monamedia'),
            'VINH-LONG' => __('Vĩnh Long', 'monamedia'),
            'VINH-PHUC' => __('Vĩnh Phúc', 'monamedia'),
            'YEN-BAI' => __('Yên Bái', 'monamedia'),
        );

        return $states;
    }

}
