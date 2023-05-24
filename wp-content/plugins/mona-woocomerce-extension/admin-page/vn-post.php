<?php
$active = get_option('m_vn_post_active');
$userName =  get_option('m_vn_post_user_name');
$password =  get_option('m_vn_post_user_password');
$products =  get_option('m_vn_post_products');
$province =  get_option('m_vn_post_provinces_user');
$district =  get_option('m_vn_post_district_user');
 
?>
<div class="mona-wrapp-setting">
    <form method="post" action="options.php">
        <table class="form-table">
            <tbody>
                <?php settings_fields('mona_api_vn_post_setting'); ?>
                <?php do_settings_sections('mona_api_vn_post_setting'); ?>
                <tr>
                    <th scope="row"><?php _e('Bật VNPost', 'monamedia') ?></th>
                    <td>
                        <?php $checked = ($active == 'yes' ? 'checked' : ''); ?>
                        <input type="checkbox" name="m_vn_post_active" value="yes" <?php echo $checked ?> class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Bật Products', 'monamedia') ?></th>
                    <td>
                        <?php $checked = ($products == 'yes' ? 'checked' : ''); ?>
                        <input type="checkbox" name="m_vn_post_products" value="yes" <?php echo $checked ?> class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('User Name', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="m_vn_post_user_name" value="<?php echo $userName ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Password', 'monamedia') ?></th>
                    <td>
                        <input type="password" name="m_vn_post_user_password" value="<?php echo $password ?>" class="regular-text ">
                    </td>
                </tr>
                <?php $provinces = vn_post_get_province();
                $districtCurrent =  false;
                if (is_array($provinces) and $provinces['status'] = 'success') {
                    $data = $provinces['data'];
                ?>
                    <tr>
                        <th scope="row"><?php _e('Tên tỉnh/thành phố nơi lấy hàng hóa', 'monamedia') ?></th>
                        <td>
                            <select name="m_vn_post_provinces_user" class="regular-text" id="m_vn_post_provinces_user">
                                <option value="" selected disabled>Chọn tỉnh thành</option>
                                <?php
                                foreach ($data as $key => $value) {
                                    $districtData = json_encode($value['District']);
                                    $selected = $key == $province ? 'selected' : '';
                                    if ($key == $province) {
                                        $districtCurrent = $value['District'];
                                    }
                                ?>
                                    <option <?php echo $selected ?> data-district='<?php echo $districtData ?>' value="<?php echo $key ?>"><?php echo $value['NameProvince'] ?></option>
                                <?php
                                } ?>
                            </select>
                        </td>
                    </tr>
                <?php
                } ?>

                <tr>
                    <th scope="row"><?php _e('Tên Quận/huyện nơi lấy hàng hóa', 'monamedia') ?></th>
                    <td>
                        <select name="m_vn_post_district_user" class="regular-text" id="m_vn_post_district_user">
                            <option value="" selected disabled>Chọn Quận/huyện</option>
                            <?php if ($districtCurrent) {
                                foreach ($districtCurrent as $key => $value) {
                                    $selected = $district == $key ? 'selected' : '';
                            ?>
                                    <option <?php echo $selected ?> value="<?php echo $key ?>"><?php echo $value ?></option>
                            <?php
                                }
                            } ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button(); ?>

    </form>
</div>