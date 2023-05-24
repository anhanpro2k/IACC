<?php

class Mona_remote {

    protected $gh_tk_token;

    public function __construct() {
        $this->gh_tk_token = get_option('giao_hang_tiet_kiem_api');
    }

    public function gh_tk_get_shipping($args) {
        $active = get_option('giao_hang_tiet_kiem_active');
        if($active !='yes'){
            return;
        }
        $data = array(
            "pick_province" => "Hà Nội",
            "pick_district" => "Quận Hai Bà Trưng",
            "province" => "Hà nội",
            "district" => "Quận Cầu Giấy",
            "address" => "P.503 tòa nhà Auu Việt, số 1 Lê Đức Thọ",
            "weight" => 1000,
            "value" => 3000000,
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://services.giaohangtietkiem.vn/services/shipment/fee?" . http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => array(
        "Token: APITokenSample-ca441e70288cB0515F310742",
    ),
        ));

        $response = curl_exec($curl);
       
        curl_close($curl);
         return $response;
    }

}
