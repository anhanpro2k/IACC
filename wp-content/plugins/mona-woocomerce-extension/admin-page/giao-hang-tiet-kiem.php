

<?php
$active = get_option('giao_hang_tiet_kiem_active');
$send_order = get_option('giao_hang_tiet_kiem_send_order');
$ghtk_api = get_option('giao_hang_tiet_kiem_api');
$pick_province = get_option('ghtk_pick_province');
$pick_district = get_option('ghtk_pick_district');
$pick_ward = get_option('ghtk_pick_ward');
$pick_street = get_option('ghtk_pick_street');
$pick_address = get_option('ghtk_pick_address');
$pick_tel =get_option('ghtk_pick_tel');
$pick_name =get_option('ghtk_pick_name');
$pick_email =get_option('ghtk_pick_email');
?>
<div class="mona-wrapp-setting">
    <form method="post" action="options.php">
        <table class="form-table">
            <tbody>
                <?php settings_fields('mona_api_ghtk_setting'); ?>
                <?php do_settings_sections('mona_api_ghtk_setting'); ?>
                <tr>
                    <th scope="row"><?php _e('Bật Giao hàng tiết kiệm', 'monamedia') ?></th>
                    <td>
                        <input type="checkbox" name="giao_hang_tiet_kiem_active" value="yes" <?php echo ($active=='yes'?'checked':'');?> class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Gửi đơn hàng lên GHTK', 'monamedia') ?></th>
                    <td>
                        <input type="checkbox" name="giao_hang_tiet_kiem_send_order" value="yes" <?php echo ($send_order=='yes'?'checked':'');?> class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Giao Hàng Tiết Kiệm API', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="giao_hang_tiet_kiem_api" value="<?php echo $ghtk_api;  ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Tên người liên hệ lấy hàng hóa', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="ghtk_pick_name" value="<?php echo $pick_name;  ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Số điện thoại liên hệ nơi lấy hàng hóa', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="ghtk_pick_tel" value="<?php echo $pick_tel;  ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Emai Liên hệ nếu đơn hàng bị trả về', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="ghtk_pick_email" value="<?php echo $pick_email;  ?>" class="regular-text ">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><?php _e('Tên tỉnh/thành phố nơi lấy hàng hóa', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="ghtk_pick_province" value="<?php echo $pick_province;  ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Tên quận/huyện nơi lấy hàng hóa', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="ghtk_pick_district" value="<?php echo $pick_district;  ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Tên phường/xã nơi lấy hàng hóa', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="ghtk_pick_ward" value="<?php echo $pick_ward;  ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Tên đường/phố nơi lấy hàng hóa', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="ghtk_pick_street" value="<?php echo $pick_street;  ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Địa chỉ ngắn gọn để lấy nhận hàng hóa. Ví dụ: nhà số 5, tổ 3, ngách 11, ngõ 45', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="ghtk_pick_address" value="<?php echo $pick_address;  ?>" class="regular-text ">
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button(); ?>

    </form>
</div>