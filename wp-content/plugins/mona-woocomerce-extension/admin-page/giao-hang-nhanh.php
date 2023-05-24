<?php
$active = get_option('giao_hang_nhanh_active');
$send_order = get_option('giao_hang_nhanh_send_order');
$ghn_api = get_option('giao_hang_nhanh_api');
$tester = get_option('giao_hang_nhanh_tester');
$pick_district = get_option('ghn_pick_district');
$pick_address = get_option('ghn_pick_address');
$service_id = get_option('ghn_service_id');
$name = get_option('ghn_contact_name');
$phone = get_option('ghn_contact_phone');
$id_shop = get_option('ghn_id_shop');
?>
<div class="mona-wrapp-setting">
    <form method="post" action="options.php">
        <table class="form-table">
            <tbody>
                <?php settings_fields('mona_api_ghn_setting'); ?>
                <?php do_settings_sections('mona_api_ghn_setting'); ?>
                <tr>
                    <th scope="row"><?php _e('Bật Giao hàng nhanh', 'monamedia') ?></th>
                    <td>
                        <input type="checkbox" name="giao_hang_nhanh_active" value="yes" <?php echo ($active == 'yes' ? 'checked' : ''); ?> class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Gửi đơn hàng lên GHN', 'monamedia') ?></th>
                    <td>
                        <input type="checkbox" name="giao_hang_nhanh_send_order" value="yes" <?php echo ($send_order == 'yes' ? 'checked' : ''); ?> class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Giao Hàng Nhanh API', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="giao_hang_nhanh_api" value="<?php echo $ghn_api; ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Chế Độ Test', 'monamedia') ?></th>
                    <td>
                        <input type="checkbox" name="giao_hang_nhanh_tester" <?php echo ($tester == 'yes' ? 'checked="checked"' : ''); ?> value='yes' class="regular-text ">
                    </td>
                </tr>

                <tr>
                    <th scope="row"><?php _e('Nơi Lấy Hàng (Quận/ Huyện)', 'monamedia') ?></th>
                    <td>
                        <select name="ghn_pick_district" id="mona-select2-option" class="regular-text ">
                            <?php
                            // $datas = mona_ghn_get_distric_id();  
                            $datas = mona_ghn_get_province();
                            if (is_array($datas)) {
                                foreach ($datas as $data) {

                                    $provinceId = $data['ProvinceID'];

                                    $districts =  mona_ghn_get_district_by_province($provinceId);
                                    if (is_array($districts)) {
                                        foreach ($districts  as $key => $value) {
                                            $select = '';
                                            $text = $data['ProvinceName'];
                                          
                                            $text .=  ' - ' . $value['DistrictName'];
                                            $valueOption = $value['DistrictID'] . '-' .  $provinceId  ;
                                            if ($valueOption == $pick_district) {
                                                $select = 'selected="selected"';
                                            }
                                            echo '<option ' . $select . ' value="' . $valueOption . '">' . $text . '</option>';
                                        }
                                    } 
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr> 
                <tr>
                    <th scope="row"><?php _e('Nơi Lấy Hàng (Địa chỉ)', 'monamedia') ?></th>

                    <td>
                        <input type="text" name="ghn_pick_address" value="<?php echo $pick_address; ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Tên liên hệ lấy hàng', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="ghn_contact_name" value="<?php echo $name; ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Điện thoại liên hệ lấy hàng', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="ghn_contact_phone" value="<?php echo $phone; ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('loại dịch vụ giao hàng', 'monamedia') ?></th>
                    <td>
                        <select name="ghn_service_id">
                            <option value="53337" <?php echo ($service_id != '53337' ? 'selected="selected"' : '') ?>>Giao Hàng Nhanh</option>
                            <option value="53320" <?php echo ($service_id == '53320' ? 'selected="selected"' : '') ?>>Giao Hàng Chuẩn</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('ID cửa hàng', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="ghn_id_shop" value="<?php echo $id_shop; ?>" class="regular-text ">
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button(); ?>

    </form>
    &copy; All right reserved.
     <img src="https://mona-media.com/logo.png" style="width:20px;vertical-align:sub;" alt="MonaMedia">
    <a href="https://mona-media.com/" title="Công ty thiế kế website chuyên nghiệp"><strong>Mona Media</strong></a>
</div>
<script>
    jQuery(document).ready(function($) {
        $('#mona-select2-option').select2({
            placeholder: 'Select an option'
        });
    });
</script>