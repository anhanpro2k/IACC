<?php 
add_action( 'wp_ajax_mona_ajax_delete_media',  'mona_ajax_delete_media' ); // login
add_action( 'wp_ajax_nopriv_mona_ajax_delete_media',  'mona_ajax_delete_media' ); // no login
function mona_ajax_delete_media() {
    $id  = isset ( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
    $key = isset ( $_POST['key'] ) ? esc_attr( $_POST['key'] ) : 0;
    if ( $id && $key ) {
        global $woocommerce;
        $cart = $woocommerce->cart->cart_contents;
        foreach ( $cart as $cart_item_key => $cart_item ) {
            if ( $cart_item_key === $key ) {  
                $attachments = isset( $cart_item['attachments'] ) ? $cart_item['attachments'] : []; 
                if ( is_array ( $attachments ) ) {
                    if ( ( $search_key = array_search ( $id, $attachments) ) !== false ) {
                        unset( $attachments[$search_key] );
                    }
                }
                // check total 
                $totalLists = count( $attachments );
                if ( $totalLists < 1 ) {
                    wp_send_json_error(
                        [
                            'title'   => __( 'Thông báo!', 'monamedia' ),
                            'message' => __( 'Bạn phải có ít nhất là 1 ảnh.', 'monamedia' ),
                            'action'  => [
                                'title_close' => '<span class="text">' . __( 'Đóng', 'monamedia' ) . '</span>',
                            ],
                        ]
                    );
                    wp_die();
                }
                $woocommerce->cart->cart_contents[$key]['attachments'] = $attachments;
            }
        }
    } else {
        wp_send_json_error(
            [
                'title'   => __( 'Thông báo!', 'monamedia' ),
                'message' => __( 'Thao tác thất bại. Thử lại!', 'monamedia' ),
                'action'  => [
                    'title_close' => '<span class="text">' . __( 'Đóng', 'monamedia' ) . '</span>',
                ],
            ]
        );
        wp_die();
    }
    $woocommerce->cart->set_session();
    echo wp_send_json_success(
        [
            'title'     => __( 'Thông báo!', 'monamedia' ),
            'message'   => __( 'Xoá hình ảnh thành công.', 'monamedia' ),
            'action'    => [
                'title_close' => '<span class="text">' . __( 'Đóng', 'monamedia' ) . '</span>',
            ],
            'template' => get_review_media( $attachments, $key ),
        ]
    );
    wp_die();
}