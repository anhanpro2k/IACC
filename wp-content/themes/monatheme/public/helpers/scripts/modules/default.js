export default function MonaCreateModuleDefault() {
    $('.prodt-info-ct .prodt-select select').on('change', function () {
        var $pa_mau_sac = $('input[name="attributes[pa_mau-sac]"]:checked').val();
        var $pa_kich_thuoc = $('select[name="attributes[pa_kich-thuoc]"]').val();
        var $product_id = $('input[type="hidden"][name="product_id"]').val();

        // Your code here
        // You can now use the $pa_mau_sac and $pa_kich_thuoc variables with their selected values

        var loading = $('.pro-item-price.is-loading-group');
        if (!loading.hasClass('loading')) {
            $.ajax({
                url: mona_ajax_url.ajaxURL,
                type: 'post',
                data: {
                    action: 'mona_ajax_load_price',
                    mau: $pa_mau_sac,
                    product_id: $product_id,
                    kich_thuoc: $pa_kich_thuoc
                },
                error: function (request) {
                    loading.removeClass('loading');
                },
                beforeSend: function (response) {
                    loading.addClass('loading');
                },
                success: function (result) {
                    if (result.success) {

                        if (result.data.cart_html) {
                            $('.pro-item-price').html(result.data.cart_html);
                        }
                        loading.removeClass('loading');
                    }
                    loading.removeClass('loading');
                }
            });

        }
    });

    $('.color-item.recheck-item input').on('change', function () {

        var $pa_mau_sac = $('input[name="attributes[pa_mau-sac]"]:checked').val();
        var $pa_kich_thuoc = $('select[name="attributes[pa_kich-thuoc]"]').val();
        var $product_id = $('input[type="hidden"][name="product_id"]').val();

        // Your code here
        // You can now use the $pa_mau_sac and $pa_kich_thuoc variables with their selected values

        var loading = $('.pro-item-price.is-loading-group');
        if (!loading.hasClass('loading')) {
            $.ajax({
                url: mona_ajax_url.ajaxURL,
                type: 'post',
                data: {
                    action: 'mona_ajax_load_price',
                    mau: $pa_mau_sac,
                    product_id: $product_id,
                    kich_thuoc: $pa_kich_thuoc
                },
                error: function (request) {
                    loading.removeClass('loading');
                },
                beforeSend: function (response) {
                    loading.addClass('loading');
                },
                success: function (result) {
                    if (result.success) {

                        if (result.data.cart_html) {
                            $('.pro-item-price').html(result.data.cart_html);
                        }
                        loading.removeClass('loading');
                    }
                    loading.removeClass('loading');
                }
            });

        }
    });


    $(document).ready(function () {
        $('.btn-remove').click(function (e) {
            e.preventDefault();
            $('.recheck-block input[type="radio"]').prop('checked', false);
            $('.cate-item.recheck-item.active').removeClass('active');
        });


        // On change of select option
        $('.option-filter').change(function () {
            // Get the selected value
            var selectedValue = $(this).val();

            // Set the value to the hidden input
            $('input[name="sortby"]').val(selectedValue);

            // Submit the form with id productListForm
            $('#productListForm').submit();
        });
    });
}