<?php
if ( $order ) :
	$order_id = $order->get_id();
	?>
    <div class="success-product">
        <div class="bg-success">
            <img src="<?php echo get_site_url(); ?>/template/assets/images/bnsuccess.png" alt="">
        </div>
        <div class="success-product-content">
            <div class="container">
                <div class="box-alert-product">
                    <div class="box-item-pro">
                        <div class="item-product">
                            <div class="item-product-con">
                                1
                            </div>

                        </div>
                        <p class="text-pro">
                            Thông tin thanh toán
                        </p>
                    </div>
                    <span class="line-pro"></span>
                    <div class="box-item-pro">
                        <div class="item-product">
                            <div class="item-product-con">
                                <i class="fas fa-check"></i>
                            </div>

                        </div>
                        <p class="text-pro">
                            Hoàn tất đơn hàng
                        </p>
                    </div>
                </div>
                <div class="box-success-product">
                    <div class="img-success">
                        <img src="<?php echo get_site_url(); ?>/template/assets/images/success 1.svg" alt="">
                    </div>
                    <div class="title">
                        <p class="title-text">
                            Đặt hàng thành công
                        </p>
                    </div>
                    <div class="content">
                        <p class="content-text">
                            Đơn hàng đã thiết lập thành công. Chúng tôi sẽ liên lạc trực tiếp với quý khách để xác
                            nhận.
                        </p>
                    </div>
                </div>
                <div class="content-success-con d-wrap">
                    <div class="con-left d-item d-2">
                        <div class="content-left">
                            <div class="title">
                                <p class="title-text">
									<?php echo __( 'Tóm tắt đơn hàng', 'monamedia' ); ?>
                                </p>
                            </div>

                            <div class="con-text d-wrap">
                                <div class="con-text-box d-item d-2">
                                    <div class="item-con-t">
                                        <p>
											<?php echo __( 'Mã đơn hàng', 'monamedia' ); ?>
                                        </p>
                                    </div>
                                    <div class="item-con-b">
                                        <p>
                                            #<?php echo esc_html( $order_id ); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="con-text-box d-item d-2">
                                    <div class="item-con-t">
                                        <p>
											<?php echo __( 'Ngày', 'monamedia' ); ?>
                                        </p>
                                    </div>
                                    <div class="item-con-b">
                                        <p>
											<?php echo esc_html( $order->get_date_created()->format( 'd/m/Y' ) ); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="con-text-box d-item d-2">
                                    <div class="item-con-t">
                                        <p>
											<?php echo __( 'Tổng cộng', 'monamedia' ); ?>
                                        </p>
                                    </div>
                                    <div class="item-con-b price-red">
                                        <p>
											<?php echo $order->get_formatted_order_total(); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="con-text-box d-item d-2">
                                    <div class="item-con-t">
                                        <p>
											<?php echo __( 'Hình thức thanh toán', 'monamedia' ); ?>
                                        </p>
                                    </div>
                                    <div class="item-con-b">
                                        <p>
											<?php echo $order->get_payment_method_title(); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <div class="title">
                                <p class="title-text">
									<?php echo __( 'Thông tin chuyển khoản', 'monamedia' ); ?>
                                </p>
                            </div>
                            <div class="box-bank">
                                <div class="title">
                                    <p class="title-text">
                                        Ngân hàng Á Châu (ACB)
                                    </p>
                                    <div class="img-bank"><img
                                                src="<?php echo esc_url( get_site_url() ); ?>/template/assets/images/bank.svg"
                                                alt=""></div>
                                </div>
                                <div class="content">
                                    <p>Số tài khoản: 1234 5678 9123</p>
                                    <p>Chi nhánh: Tân Bình</p>
                                    <p>Người thụ hưởng: Nguyễn Ngọc Tuấn</p>
                                </div>
                            </div>
                            <div class="btn-box d-wrap">
                                <a href="<?php echo get_permalink( MONA_WC_PRODUCTS ); ?>"
                                   class="btn d-item center-button">
                <span class="btn-inner">
                    <span class="text"><?php echo __( 'Tiếp tục mua sắm', 'monamedia' ); ?></span>
                </span>
                                </a>
                                <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="btn d-item">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="con-right d-item d-2">
                        <div class="content-right">
                            <div class="title">
                                <p class="title-text">
									<?php echo __( 'Thông tin đơn hàng', 'monamedia' ); ?>
                                </p>
                            </div>
                            <div class="content">
                                <div class="product-item-wrap">
									<?php
									foreach ( $order->get_items() as $item_id => $item ) {
										?>
                                        <div class="item-product-wrap">
                                            <div class="item-product">
                                                <div class="item-img">
                                                    <img src="<?php echo get_the_post_thumbnail_url( $item->get_product_id(), 'medium-square' ); ?>"
                                                         alt="">
                                                </div>
                                                <div class="item-content">
                                                    <p class="title-con">
														<?php echo $item->get_name(); ?>
                                                    </p>
                                                    <div class="btn-delete">
                                                        <i class="fas fa-times-circle" style="color: #fa0000"></i>
                                                    </div>
                                                    <div class="box-quantity">
                                                        <p class="quantity">
															<?php echo __( 'Số lượng', 'monamedia' ); ?>
                                                            : <?php echo $item->get_quantity(); ?>
                                                        </p>
                                                        <div class="price">
															<?php
															if ( $item->get_product()->is_on_sale() ) {
																?>
                                                                <p class="price-text">
																	<?php echo number_format( $item->get_product()->get_sale_price() ); ?>
                                                                    đ
                                                                </p>
                                                                <div class="price-line">
                                                                    (<?php echo number_format( $item->get_product()->get_regular_price() ); ?>
                                                                    đ)
                                                                </div>
																<?php
															} else { ?>
                                                                <p class="price-text">
																	<?php echo number_format( $item->get_product()->get_regular_price() ); ?>
                                                                    đ
                                                                </p>
																<?php
															} ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-list-image">
                                                <div class="title-list-img">
                                                    <p class="title-text">
                                                        Bạn đã tải lên <strong>6 hình ảnh</strong> để in cho sản phẩm
                                                        này
                                                    </p>
                                                    <p class="number">
                                                        6/64
                                                    </p>
                                                </div>
                                                <div class="content-list-cart">
                                                    <div class="content-list-cart-con">
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                        <div class="img-item-con">
                                                            <img
                                                                    src="<?php echo get_site_url(); ?>/template/assets/images/Frame 14368.png"
                                                                    alt="">
                                                            <div class="btn-delete-img">
                                                                <i class="fas fa-times-circle"
                                                                   style="color: #fa0000"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="note-product">
                                                    <p class="text-note">
                                                        Ghi chú: Vorem ipsum dolor sit amet, consectetur adipiscing
                                                        elit. Nunc
                                                        vulputate libero et velit interdum, ac aliquet odio mattis.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
										<?php
									} ?>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
endif;
?>