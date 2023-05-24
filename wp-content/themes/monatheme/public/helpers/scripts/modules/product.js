function AlertCustom(type = 'success', title = "", desc = '') {
    if (type == "error") {
        var icon_alert = '<i class="m-css-icon fas fa-times-circle"></i>';
        var title = title != "" ? title : "Lỗi";
        var desc = desc != "" ? desc : "Đã xảy ra lỗi nghiệm trọng";
    } else if (type == "warning") {
        var icon_alert = '<i class="m-css-icon  fas fa-exclamation-circle"></i>';
        var title = title != "" ? title : "Cảnh báo";
        var desc = desc != "" ? desc : "Vui lòng kiểm tra lại";
    } else {
        var icon_alert = '<i class="m-css-icon fas fa-check-circle"></i>';
        var title = title != "" ? title : "Thành công";
        var desc = desc != "" ? desc : "Hành động đã thành công";
    }
    var html_default = `
            <div class="alert showAlert show ${type}">
                <div class="msg-icon">${icon_alert}</div>
                <div class="msg-info">
                <span class="msg">
                <span class="m-bold-text">${title}</span>
                <span class="m-main-text"> ${desc}</span>
                </span>
                </div>
                <div class="close-btn">
                <span class="fas fa-times"></span>
                </div>
            </div>
        `;
    let check_empty = jQuery('.alert').hasClass('show');

    if (!check_empty) {
        $("body").append(html_default);
        setTimeout(function () {
            jQuery('.alert').removeClass("show");
            jQuery('.alert').addClass("hide");
            setTimeout(() => {
                jQuery('.alert').remove();
            }, 1000);
        }, 3000);
    }

    jQuery('.close-btn').click(function () {
        jQuery('.alert').removeClass("show");
        jQuery('.alert').addClass("hide");
        setTimeout(() => {
            jQuery('.alert').remove();
        }, 1000);
    });
}

export default function MonaCreateModuleProduct() {
    function AddToCart(formdata, act, loading) {
        if (!loading.hasClass('loading')) {
            $.ajax({
                url: mona_ajax_url.ajaxURL,
                type: 'post',
                data: {
                    action: 'mona_ajax_add_to_cart',
                    formdata: formdata,
                    type: act
                },
                error: function (request) {
                    loading.removeClass('loading');
                },
                beforeSend: function (response) {
                    loading.addClass('loading');
                },
                success: function (result) {
                    if (result.success) {
                        if (act == 'buy_now' && result.data.redirect) {
                            window.location.href = result.data.redirect;
                        } else {
                            AlertCustom('success', result.data.action.title, result.data.message);
                            loadProductCart();
                            $('#monaCartQty').html(result.data.cart_data.count);
                            $(document.body).trigger('wc_fragment_refresh');
                        }
                        loading.removeClass('loading');
                    } else {
                        AlertCustom('error', result.data.action.title, result.data.message);
                        loading.removeClass('loading');
                    }
                }
            });

        }
    }

    $(document).on('click', '.mona-buy-now', function (e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.closest('#frmAddProduct').length) {
            var dataString = $this.closest('#frmAddProduct').serialize();
        } else if ($('#frmAddProduct').length) {
            var dataString = $('#frmAddProduct').serialize();
        } else {
            var dataString = $this.closest('#frmAddProduct').serialize();
        }

        var act = 'buy_now';
        var loading = $this;
        var form_data = new FormData();
        // add action ajax
        form_data.append('action', 'mona_ajax_add_to_cart');
        // get multi files
        var totalFiles = $('input[name="images"]').prop('files') || [];
        if ( totalFiles && totalFiles.length > 0 ) {
            for (var i = 0; i < totalFiles.length; ++i) {
                form_data.append('images[]', totalFiles[i]);
            }
        }
        // add form data
        form_data.append('formdata', dataString);
        if (form_data && form_data != null) {
            $.ajax({
                url: mona_ajax_url.ajaxURL,
                contentType: false,
                processData: false,
                type: 'post',
                data: form_data,
                dataType: 'json',
                error: function(request) {
                    loading.removeClass('loading');
                },
                beforeSend: function(response) {
                    loading.addClass('loading');
                },
                success: function(result) {
                    if (result.success) {
                        if (act == 'buy_now' && result.data.redirect) {
                            window.location.href = result.data.redirect;
                        } else {
                            AlertCustom('success', result.data.action.title, result.data.message);
                            loadProductCart();
                            $('#monaCartQty').html(result.data.cart_data.count);
                            $(document.body).trigger('wc_fragment_refresh');
                        }
                        loading.removeClass('loading');
                    } else {
                        AlertCustom('error', result.data.action.title, result.data.message);
                        loading.removeClass('loading');
                    }
                }
            });
        }
        //AddToCart(formdata, act, loading);
    });

}