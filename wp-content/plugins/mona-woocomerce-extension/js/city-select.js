jQuery(function ($) {

    // wc_city_select_params is required to continue, ensure the object exists
    // wc_country_select_params is used for select2 texts. This one is added by WC
    if (typeof wc_country_select_params === 'undefined' || typeof wc_city_select_params === 'undefined') {
        return false;
    }

    function getEnhancedSelectFormatString() {
        var formatString = {
            formatMatches: function (matches) {
                if (1 === matches) {
                    return wc_country_select_params.i18n_matches_1;
                }

                return wc_country_select_params.i18n_matches_n.replace('%qty%', matches);
            },
            formatNoMatches: function () {
                return wc_country_select_params.i18n_no_matches;
            },
            formatAjaxError: function () {
                return wc_country_select_params.i18n_ajax_error;
            },
            formatInputTooShort: function (input, min) {
                var number = min - input.length;

                if (1 === number) {
                    return wc_country_select_params.i18n_input_too_short_1;
                }

                return wc_country_select_params.i18n_input_too_short_n.replace('%qty%', number);
            },
            formatInputTooLong: function (input, max) {
                var number = input.length - max;

                if (1 === number) {
                    return wc_country_select_params.i18n_input_too_long_1;
                }

                return wc_country_select_params.i18n_input_too_long_n.replace('%qty%', number);
            },
            formatSelectionTooBig: function (limit) {
                if (1 === limit) {
                    return wc_country_select_params.i18n_selection_too_long_1;
                }

                return wc_country_select_params.i18n_selection_too_long_n.replace('%qty%', limit);
            },
            formatLoadMore: function () {
                return wc_country_select_params.i18n_load_more;
            },
            formatSearching: function () {
                return wc_country_select_params.i18n_searching;
            }
        };

        return formatString;
    }

    // Select2 Enhancement if it exists
    if ($().select2) {
        var wc_city_select_select2 = function () {
            $('select.city_select:visible').each(function () {
                var select2_args = $.extend({
                    placeholderOption: 'first',
                    width: '100%'
                }, getEnhancedSelectFormatString());

                $(this).select2(select2_args);
            });
        };

        wc_city_select_select2();

        $(document.body).bind('city_to_select', function () {
            wc_city_select_select2();
        });
    }

    /* City select boxes */
    var cities_json = wc_city_select_params.cities.replace(/&quot;/g, '"');
    var cities = $.parseJSON(cities_json);

    $('body').on('country_to_state_changing', function (e, country, $container) {
        var $statebox = $container.find('#billing_state, #shipping_state, #calc_shipping_state');
        var state = $statebox.val();
        $(document.body).trigger('state_changing', [country, state, $container]);
    });

    $('body').on('change', 'select.state_select, #calc_shipping_state', function () {
        var $container = $(this).closest('div');
        var country = $container.find('#billing_country, #shipping_country, #calc_shipping_country').val();
        var state = $(this).val();

        $(document.body).trigger('state_changing', [country, state, $container]);
    });

    $('body').on('state_changing', function (e, country, state, $container) {
        var $citybox = $container.find('#billing_city, #shipping_city, #calc_shipping_city');

        if (cities[country]) {
            /* if the country has no states */
            if (cities[country] instanceof Array) {
                cityToSelect($citybox, cities[country]);
            } else if (state) {
                if (cities[country][state]) {
                    cityToSelect($citybox, cities[country][state]);
                } else {
                    cityToInput($citybox);
                }
            } else {
                disableCity($citybox);
            }
        } else {
            cityToInput($citybox);
        }
    });

    /* Ajax replaces .cart_totals (child of .cart-collaterals) on shipping calculator */
    if ($('.cart-collaterals').length && $('#calc_shipping_state').length) {
        var calc_observer = new MutationObserver(function () {
            $('#calc_shipping_state').change();
        });
        calc_observer.observe(document.querySelector('.cart-collaterals'), {
            childList: true
        });
    }

    function cityToInput($citybox) {
        if ($citybox.is('input')) {
            $citybox.prop('disabled', false);
            return;
        }

        var input_name = $citybox.attr('name');
        var input_id = $citybox.attr('id');
        var placeholder = $citybox.attr('placeholder');

        $citybox.parent().find('.select2-container').remove();

        $citybox.replaceWith('<input type="text" class="input-text" name="' + input_name + '" id="' + input_id + '" placeholder="' + placeholder + '" />');
    }

    function disableCity($citybox) {
        $citybox.val('').change();
        $citybox.prop('disabled', true);
    }

    function cityToSelect($citybox, current_cities) {
        var value = $citybox.val();

        if ($citybox.is('input')) {
            var input_name = $citybox.attr('name');
            var input_id = $citybox.attr('id');
            var placeholder = $citybox.attr('placeholder');

            $citybox.replaceWith('<select name="' + input_name + '" id="' + input_id + '" class="city_select" placeholder="' + placeholder + '"></select>');
            //we have to assign the new object, because of replaceWith
            $citybox = $('#' + input_id);
        } else {
            $citybox.prop('disabled', false);
        }

        var options = '';
        for (var index in current_cities) {
            if (current_cities.hasOwnProperty(index)) {
                var cityName = current_cities[index];
                options = options + '<option value="' + cityName + '">' + cityName + '</option>';
            }
        }

        $citybox.html('<option value="">' + wc_city_select_params.i18n_select_city_text + '</option>' + options);

        if ($('option[value="' + value + '"]', $citybox).length) {
            $citybox.val(value).change();
        } else {
            $citybox.val('').change();
        }

        $(document.body).trigger('city_to_select');
    }
    const sendAjaxPromise = (url, type) => new Promise((resolve, reject) => {
        $.ajax({
            url: url,
            type: type,
            // data: data,
            headers: {
                "token": "bade32bb-a97e-11eb-9035-ae038bcc764b",
                "cache-control": "no-cache",
                "postman-token": "e5a08868-7575-1906-1936-b52685944349"
            },
            success: function (result) {
                resolve(result);
            },
            error: function (error) {
                reject(error);
            }
        });
    });


    async function change_billing($this) {
        let cityName = $this.find('option:selected').val();
        let provinceName = $('#billing_state').find('option:selected').text();
        let provinceId = await get_province_data(provinceName);

        var form = new FormData();
        form.append("province_id", `${provinceId}`);
        // for (var key of form.entries()) {
        //     console.log(key[0] + ', ' + key[1]);
        // }
        var settings = {
            // "async": true,
            // "crossDomain": true,
            // "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/district",
            // "method": "GET",
            // "headers": {
            //     "token": "bade32bb-a97e-11eb-9035-ae038bcc764b",
            //     "cache-control": "no-cache",
            //     "postman-token": "6ba0c60f-26c7-f2e8-dd40-d31906fbaa03"
            // }
            "async": true,
            "crossDomain": true,
            "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/district?province_id=" + provinceId,
            "method": "POST",
            "headers": {
                "token": "bade32bb-a97e-11eb-9035-ae038bcc764b",
                "cache-control": "no-cache",
                "postman-token": "4efe58b2-d7b8-46e3-733a-18140c334ef3"
            },
            "processData": false,
            "contentType": false,
            "mimeType": "multipart/form-data",
            "data": form
        }
        let defaultWas = $('#billing_address_1').val();
        let CityId = 0;
        $.ajax(settings).done(function (response) {
            let $responseDistr = JSON.parse(response);
            console.log($responseDistr);
            if ($responseDistr.code == '200') {
                let data = $responseDistr.data;
                jQuery.each(data, function (e, i) {
                    if ($.inArray(cityName, i.NameExtension) !== -1 && i.ProvinceID === provinceId) {
                        CityId = i.DistrictID
                        let url = "https://online-gateway.ghn.vn/shiip/public-api/master-data/ward?district_id=" + CityId;
                        var settings = {
                            "async": true,
                            "crossDomain": true,
                            "url": url,
                            "method": "GET",
                            "headers": {
                                "token": "bade32bb-a97e-11eb-9035-ae038bcc764b",
                                "cache-control": "no-cache",
                                "postman-token": "43931792-0d22-190a-a59e-deb0c0d49655"
                            },
                            "processData": false,
                            "contentType": false,
                            "mimeType": "multipart/form-data",
                            // "data": form
                        }
                        $.ajax(settings).done(function (response) {
                            let $response = JSON.parse(response);
                            if ($response.code == '200') {
                                let $citybox = $('#billing_address_1');
                                var input_name = $citybox.attr('name');
                                var input_id = $citybox.attr('id');
                                var placeholder = $citybox.attr('placeholder');
                                $citybox.replaceWith('<select name="' + input_name + '" id="' + input_id + '" class="city_select form__input rs__form" placeholder="' + placeholder + '"></select>');
                                let data = $response.data;
                                var options = '';
                                $citybox = $('#' + input_id);
                                jQuery.each(data, function (e, i) {
                                    let cityName = i.WardName,
                                        cityCode = i.WardCode,
                                        selected = '';

                                    if (cityName === defaultWas) {
                                        selected = 'selected';
                                    }
                                    options = options + '<option ' + selected + ' value="' + cityName + '" data-code="' + cityCode + '">' + cityName + '</option>';
                                });
                                $citybox.html('<option value="">Chọn một tùy chọn…</option>' + options);
                                wc_city_select_select2();
                                check_billing_city()
                            }
                        });
                    }
                });
            }
        });

    }
    $('#billing_city').on('change', function (e) {

        change_billing($(this));

        // 
        // let provinceName = $('#billing_state').find('option:selected').text();
        // get_province_data(provinceName);
    })
    change_billing($('#billing_city'));
    $(document).on('change', '#billing_address_1', function () {
        check_billing_city();
    });
    async function get_province_data(provinceName) {

        // var settings = {
        //     "async": true,
        //     "crossDomain": true,
        //     "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/province",
        //     "method": "GET",
        //     "headers": {
        //         "token": "bade32bb-a97e-11eb-9035-ae038bcc764b",
        //         "cache-control": "no-cache",
        //         "postman-token": "e5a08868-7575-1906-1936-b52685944349"
        //     }
        // }
        try {
            const result = await sendAjaxPromise("https://online-gateway.ghn.vn/shiip/public-api/master-data/province", 'GET');
            if (result.code == '200') {
                let data = result.data,
                    $result;

                jQuery.each(data, function (e, i) {
                    if ($.inArray(provinceName, i.NameExtension) !== -1) {
                        $result = i.ProvinceID;
                    }
                });
                // console.log($result);
                return $result;
            }

        } catch (e) {

            return e;
        }
        return;
        // let result = $.ajax(settings).done(function (response) {
        //     return response;
        // });
        // console.log(result.responseJSON);
        // return result;
        // .done( function (response) {
        //     // let $responseProvince = response
        //     // let result;
        //     // if ($responseProvince.code == '200') {
        //     //     let data = $responseProvince.data;

        //     //     jQuery.each(data, function (e, i) {
        //     //         if ($.inArray(provinceName, i.NameExtension) !== -1) {
        //     //             result = i.ProvinceID;
        //     //         }
        //     //     });
        //     // }
        //     // return result;
        // });


    }

    function check_billing_city() {
        let $codeId = $('#billing_address_1').find('option:selected').attr('data-code');
        $('#m_to_ward_code').val($codeId);
    }
});