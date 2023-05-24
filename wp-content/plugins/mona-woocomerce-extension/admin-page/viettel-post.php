<?php
$active = get_option('m_viettel_post_active');
$userName =  get_option('m_viettel_post_user_name');
$password =  get_option('m_viettel_post_user_password');
$products =  get_option('m_viettel_post_products');
$province =  get_option('m_viettel_post_provinces_user');
$district =  get_option('m_viettel_post_district_user');

?>
<div class="mona-wrapp-setting">
    <form method="post" action="options.php">
        <table class="form-table">
            <tbody>
                <?php settings_fields('mona_api_viettel_post_setting'); ?>
                <?php do_settings_sections('mona_api_viettel_post_setting'); ?>
                <tr>
                    <th scope="row"><?php _e('Bật Viettel Post', 'monamedia') ?></th>
                    <td>
                        <?php $checked = ($active == 'yes' ? 'checked' : ''); ?>
                        <input type="checkbox" name="m_viettel_post_active" value="yes" <?php echo $checked ?> class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Bật Products', 'monamedia') ?></th>
                    <td>
                        <?php $checked = ($products == 'yes' ? 'checked' : ''); ?>
                        <input type="checkbox" name="m_viettel_post_products" value="yes" <?php echo $checked ?> class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('User Name', 'monamedia') ?></th>
                    <td>
                        <input type="text" name="m_viettel_post_user_name" value="<?php echo $userName ?>" class="regular-text ">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Password', 'monamedia') ?></th>
                    <td>
                        <input type="password" name="m_viettel_post_user_password" value="<?php echo $password ?>" class="regular-text ">
                    </td>
                </tr>
                <?php $provinces = viettel_post_get_province();

                if (is_array($provinces)) {

                ?>
                    <tr>
                        <input type="hidden" id="viettel-token" value="<?php echo viettel_post_get_token(); ?>">
                        <th scope="row"><?php _e('Tên tỉnh/thành phố nơi lấy hàng hóa', 'monamedia') ?></th>
                        <td>
                            <select name="m_viettel_post_provinces_user" class="regular-text" id="m_viettel_post_provinces_user">
                                <option value="" selected disabled>Chọn tỉnh thành</option>
                                <?php
                                foreach ($provinces as $key => $value) {

                                    $selected = $value->PROVINCE_ID == $province ? 'selected' : '';

                                ?>
                                    <option <?php echo $selected ?> value="<?php echo $value->PROVINCE_ID ?>"><?php echo $value->PROVINCE_NAME ?></option>
                                <?php
                                } ?>
                            </select>
                        </td>
                    </tr>
                <?php
                } ?>
                <?php

                $districts = viettel_post_get_district_by($province)
                ?>
                <tr>
                    <th scope="row"><?php _e('Tên Quận/huyện nơi lấy hàng hóa', 'monamedia') ?></th>
                    <td>
                        <select name="m_viettel_post_district_user" class="regular-text" id="m_viettel_post_district_user">
                            <option value="" selected disabled>Chọn Quận/huyện</option>
                            <?php
                            foreach ($districts as $key => $value) {

                                $selected = $district == $value['DISTRICT_ID'] ? 'selected' : '';
                            ?>
                                <option <?php echo $selected ?> value="<?php echo  $value['DISTRICT_ID']  ?>"><?php echo  $value['DISTRICT_NAME'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button(); ?>

    </form>
</div>