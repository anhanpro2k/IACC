<?php
        $settings = Mona_extension::get_settings();
        ?>
        
            <div class="mona-wrapp-setting">
                <form name="woocommerce_for_vietnam" method="post">
                    <?php echo $this->message ?>

                    <input type="hidden" id="action" name="action" value="mona_save_settings">
                    <input type="hidden" id="mona_nonce" name="mona_nonce" value="<?php echo wp_create_nonce('mona_save_settings') ?>"/>
                    <table class="form-table">
                        <tbody>

                            <tr>
                                <th scope="row"><?php _e('Thay đổi hiển thị tiền VND', 'monamedia') ?></th>
                                <td>
                                    <input name="settings[change_currency_symbol][enabled]" type="checkbox"
                                           id="change_currency_symbol" value="yes"
                                           <?php
                                           if ('yes' == $settings['change_currency_symbol']['enabled'])
                                               echo 'checked="checked"'
                                               ?>>
                                    <label for="change_currency_symbol"><?php _e('Bật', 'monamedia') ?></label>
                                    <br/>
                                    <br/>
                                    <input type="text" name="settings[change_currency_symbol][text]"
                                           value="<?php echo $settings['change_currency_symbol']['text'] ?>"
                                           id="change_currency_symbol_text" class="small-text">
                                    <label for="change_currency_symbol_text"><?php _e('Điền hiển thị thay thế vd: VNĐ', 'monamedia') ?></label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('Đổi 000 cuối cùng thành K hoặc bất cứ thứ gì', 'monamedia') ?></th>
                                <td>
                                    <input name="settings[convert_price][enabled]" type="checkbox" id="convert_price"
                                           value="yes"
                                           <?php
                                           if ('yes' == $settings['convert_price']['enabled'])
                                               echo 'checked="checked"'
                                               ?>>
                                    <label for="convert_price"><?php _e('Bật', 'monamedia') ?></label>

                                    <fieldset><br/>
                                        <input type="text" name="settings[convert_price][text]"
                                               value="<?php echo $settings['convert_price']['text'] ?>"
                                               id="convert_price_text" class="small-text">
                                        <label for="convert_price_text"><?php _e('Điền hiển thị. E.g:', 'monamedia') ?>
                                            <code>K</code>, <code>nghìn</code>, <code>ngàn</code></label>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php printf(__('Chuyển đổi VND sang $ cho thanh toán paypal', 'monamedia'), 'https://docs.woocommerce.com/document/paypal-standard/') ?></th>
                                <td>
                                    <input name="settings[vnd_paypal_standard][enabled]" type="checkbox"
                                           id="vnd_paypal_standard" value="yes"
                                           <?php
                                           if ('yes' == $settings['vnd_paypal_standard']['enabled'])
                                               echo 'checked="checked"'
                                               ?>>
                                    <label for="vnd_paypal_standard"><?php _e('Bật', 'monamedia') ?></label>

                                    <fieldset><br/>
                                        <select name="settings[vnd_paypal_standard][currency]"
                                                id="vnd_paypal_standard_currency">
                                                    <?php
                                                    $paypal_supported_currencies = array(
                                                        'AUD',
                                                        'BRL',
                                                        'CAD',
                                                        'MXN',
                                                        'NZD',
                                                        'HKD',
                                                        'SGD',
                                                        'USD',
                                                        'EUR',
                                                        'JPY',
                                                        'TRY',
                                                        'NOK',
                                                        'CZK',
                                                        'DKK',
                                                        'HUF',
                                                        'ILS',
                                                        'MYR',
                                                        'PHP',
                                                        'PLN',
                                                        'SEK',
                                                        'CHF',
                                                        'TWD',
                                                        'THB',
                                                        'GBP',
                                                        'RMB',
                                                        'RUB'
                                                    );
                                                    foreach ($paypal_supported_currencies as $currency) {

                                                        if (strtoupper($currency) == $settings['vnd_paypal_standard']['currency']) {
                                                            printf('<option selected="selected" value="%1$s">%1$s</option>', $currency);
                                                        } else {
                                                            printf('<option value="%1$s">%1$s</option>', $currency);
                                                        }
                                                    }
                                                    ?>
                                        </select>
                                        <label for="vnd_paypal_standard_currency"><?php _e('Chọn Loạn tiền muốn đổi', 'monamedia') ?></label>
                                        <br/>
                                        <br/>

                                        <input name="settings[vnd_paypal_standard][rate]" type="number" step="1" min="100"
                                               id="vnd_paypal_standard_rate" style="width: 70px;"
                                               value="<?php echo $settings['vnd_paypal_standard']['rate'] ?>"
                                               <label for="vnd_paypal_standard_rate"><?php _e('Điền Tỉ Giá', 'monamedia') ?></label>
                                    </fieldset>

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