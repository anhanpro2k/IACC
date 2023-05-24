<?php
add_action( 'wp_ajax_mona_ajax_add_to_cart', 'mona_ajax_add_to_cart' ); // login
add_action( 'wp_ajax_nopriv_mona_ajax_add_to_cart', 'mona_ajax_add_to_cart' ); // no login
function mona_ajax_add_to_cart() {
	global $woocommerce;
	$form = array();
	parse_str( $_POST['formdata'], $form );

	// data string
	$product_id = isset( $form['product_id'] ) ? intval( $form['product_id'] ) : 0;
	$quantity   = isset( $form['quantity'] ) ? intval( $form['quantity'] ) : 1;
	$product    = wc_get_product( $product_id );
	$type       = isset( $_POST['type'] ) ? esc_attr( $_POST['type'] ) : 'buy_now';
	$data_store = WC_Data_Store::load( 'product' );
	// check stock
	if ( ! $product->is_in_stock() ) {
		wp_send_json_error(
			[
				'title'   => __( 'Thông báo!', 'monamedia' ),
				'message' => __( 'Sản phẩm đã hết hàng.', 'monamedia' ),
				'action'  => [
					'title_close' => __( 'Đóng', 'monamedia' ),
				],
			]
		);
		wp_die();
	}
	// data files
	$fileLists = $attachments = [];
	$images    = isset( $_FILES['images'] ) ? $fileLists = $_FILES['images'] : [];
	if ( ! empty ( $fileLists ) ) {
		$countfiles = count( $fileLists['name'] );
		if ( $countfiles > 0 ) {
			foreach ( $fileLists['name'] as $key => $value ) {
				if ( $fileLists['name'][ $key ] ) {
					// new array
					$file          = [
						'name'     => $fileLists['name'][ $key ],
						'type'     => $fileLists['type'][ $key ],
						'tmp_name' => $fileLists['tmp_name'][ $key ],
						'error'    => $fileLists['error'][ $key ],
						'size'     => $fileLists['size'][ $key ]
					];
					$_FILES        = array( 'upload_file' => $file );
					$attachment_id = mona_handle_attachment( 'upload_file', $product_id );
					if ( ! is_wp_error( $attachment_id ) ) {
						$attachments[] = intval( $attachment_id );
					}
				}
			}
		}
	}
	// add note
	$cart_note = isset( $form['note'] ) ? esc_attr( $form['note'] ) : '';
	// Clear cart before adding new item
	$woocommerce->cart->empty_cart();

	if ( empty( $product_id ) ) {
		wp_send_json_error(
			[
				'title'   => __( 'Thông báo!', 'monamedia' ),
				'message' => __( 'Sản phẩm đang được cập nhật! Vui lòng quay lại sau hoặc kiểm tra sản phẩm khác.', 'monamedia' ),
				'action'  => [
					'title'       => '<span class="text">' . __( 'Danh sách sản phẩm', 'monamedia' ) . '</span>',
					'url'         => get_the_permalink( MONA_WC_PRODUCTS ),
					'title_close' => __( 'Đóng', 'monamedia' ),
				],
			]
		);
		wp_die();
	} else {
		if ( $product->is_type( 'variable' ) ) {
			$array_attr   = [
				'attribute_pa_kich-thuoc' => $form['attributes']['pa_kich-thuoc'],
				'attribute_pa_mau-sac'    => $form['attributes']['pa_mau-sac']
			];
			$variation_id = $data_store->find_matching_product_variation( new \WC_Product( $product_id ), $array_attr );
			// Add variation to cart
			$cart_data = $woocommerce->cart->add_to_cart( $product_id, $quantity, $variation_id, array(), array(
				'attachments' => $attachments,
				'note'        => $cart_note
			) );
		} else {
			// add to cart
			$cart_data = $woocommerce->cart->add_to_cart( $product_id, $quantity, null, array(), [
				'attachments' => $attachments,
				'note'        => $cart_note
			] );
		}
		// response
		if ( $cart_data ) {
			echo wp_send_json_success(
				[
					'title'     => __( 'Thông báo!', 'monamedia' ),
					'message'   => __( 'Đã thêm sản phẩm vào giỏ hàng', 'monamedia' ),
					'action'    => [
						'title'       => '<span class="text">' . __( 'Thông báo', 'monamedia' ) . '</span>',
						'url'         => get_the_permalink( MONA_WC_CHECKOUT ),
						'title_close' => '<span class="text">' . __( 'Đóng', 'monamedia' ) . '</span>',
					],
					'cart_data' => [
						'count' => $woocommerce->cart->cart_contents_count,
						'total' => $woocommerce->cart->get_cart_total(),
					],
					'redirect'  => $type == 'buy_now' ? get_the_permalink( MONA_WC_CHECKOUT ) : ''
				]
			);
			wp_die();
		} else {
			wp_send_json_error(
				[
					'title'   => __( 'Thông báo!', 'monamedia' ),
					'message' => __( 'Thêm vào giỏ hàng không thành công', 'monamedia' ),
					'action'  => [
						'title_close' => '<span class="text">' . __( 'Đóng', 'monamedia' ) . '</span>',
					],
				]
			);
			wp_die();
		}
	}
	wp_die();
}

add_action( 'wp_ajax_mona_ajax_load_price', 'mona_ajax_load_price' ); // login
add_action( 'wp_ajax_nopriv_mona_ajax_load_price', 'mona_ajax_load_price' ); // no login
function mona_ajax_load_price() {
	$product_id    = $_POST['product_id'];
	$pa_mau_sac    = $_POST['mau'];
	$pa_kich_thuoc = $_POST['kich_thuoc'];

	$arrayAttr = [
		'attribute_pa_mau-sac'    => $pa_mau_sac,
		'attribute_pa_kich-thuoc' => $pa_kich_thuoc,
	];


	ob_start(); ?>

	<?php
	$variation_id = Variation::findVariationId( $product_id, $arrayAttr );
// Get the variation object
	$variation = wc_get_product( $variation_id );
// Get the regular price of the variation
	if ( $variation->is_on_sale() ) {
		$variation_regular_price = $variation->get_regular_price();
		$variation_sale_price    = $variation->get_sale_price();
		?>
        <div class="pro-item-price flex flex-wrap mb-18 is-loading-group">
            <span class="pro-item-cur"><?php echo number_format( $variation_sale_price, 0, '.', ',' ); ?>đ</span>
            <span class="pro-item-old">(<?php echo number_format( $variation_regular_price, 0, '.', ',' ); ?>đ)</span>
        </div>
		<?php
	} else {
		$variation_regular_price = $variation->get_regular_price();
		?>
        <div class="pro-item-price flex flex-wrap mb-18 is-loading-group">
            <span class="pro-item-cur"><?php echo number_format( $variation_regular_price, 0, '.', ',' ); ?>đ</span>
        </div>
		<?php
	}
	?>


	<?php
	echo wp_send_json_success(
		[
			'title'       => __( 'Thông báo!', 'monamedia' ),
			'message'     => __( 'Load lại giỏ hàng thành công!', 'monamedia' ),
			'title_close' => __( 'Đóng', 'monamedia' ),
			'cart_html'   => ob_get_clean()
		]
	);
	wp_die();
}


