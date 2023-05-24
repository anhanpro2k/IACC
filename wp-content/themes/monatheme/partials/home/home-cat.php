<?php
/**
 * Section name: Home Cat
 * Description: Show home cat with tab - panel
 * Author: Monamedia
 * Order: 2
 */
?>
<?php
$mona_home_tab_products = get_field( 'mona_home_tab_products' );
?>

<?php
if ( content_exists( $mona_home_tab_products ) ) {
?>
<div class="protab tabJS overflow-hidden load-ele">
    <div class="protab-tab mb-48">
        <div class="container">
            <div class="protab-tab-wrap free-slide">
                <div class="swiper">
                    <div class="swiper-wrapper">
						<?php
						$delay = 100;
						foreach ( $mona_home_tab_products as $key_product => $item_product ) {
							?>
                            <div class="swiper-slide">
                                <div class="protab-item tabBtn" data-aos="flip-down" data-aos-delay="<?php echo $delay ?>">
									<?php echo $item_product['products_title']; ?>
                                </div>
                            </div>
							<?php

							$delay+=200;
						}
						?>
                        <div class="swiper-slide">
                            <a href="<?php echo get_permalink( MONA_WC_PRODUCTS ) ?>" class="protab-item"  data-aos="flip-down" data-aos-delay="1200">
								<?php echo __( 'Xem thêm', 'monamedia' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="protab-ct">
        <div class="protab-ct-wrap tabPanelList">
			<?php
			foreach ( $mona_home_tab_products as $key_tab => $item_tab ) {
				{
					$argsProduct = array(
						'post_type'   => 'product',
						'post_status' => 'publish',
						'meta_query'  => [
							'relation' => 'AND',
						],
						'tax_query'   => [
							'relation' => 'AND',
						]
					);

					$products_option = $item_tab['products_option'];

					switch ( $products_option ) {
						case 'product_cat':
							$products_by_product_cat = $item_tab['products_by_product_cat'];
							if ( ! empty( $products_by_product_cat ) ) {
								$argsProduct['tax_query'][] = [
									'taxonomy' => 'product_cat',
									'field'    => 'ID',
									'terms'    => $products_by_product_cat,
								];
							}
							break;

						case 'custom':
							$products_custom = $item_tab['products_custom'];
							if ( ! empty( $products_custom ) ) {
								$argsProduct['post__in'] = (array) $products_custom;
								$argsProduct['orderby']  = 'post__in';
							}
							break;

						default:
							break;
					}

					$postperpage = $item_tab['products_postperpage'];
					$sort        = $item_tab['products_sort'];

					if ( $products_option != 'custom' ) {

						if ( ! empty( $postperpage ) ) {
							$argsProduct['posts_per_page'] = $postperpage;
						} else {
							$argsProduct['posts_per_page'] = 12;
						}

						switch ( $sort ) {
							case 'asc':
								$argsProduct['order'] = 'ASC';
								break;
							case 'top':
								$argsProduct['orderby']  = 'meta_value_num';
								$argsProduct['order']    = 'DESC';
								$argsProduct['meta_key'] = 'total_sales';
								break;
							case 'sale':
								$argsProduct['meta_query'] = WC()->query->get_meta_query();
								$argsProduct['post__in']   = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
								break;
							case 'view':
								$argsProduct['orderby']  = 'meta_value_num';
								$argsProduct['order']    = 'DESC';
								$argsProduct['meta_key'] = '_mona_post_view';
								break;
							default:
								$argsProduct['order'] = 'DESC';
								break;
						}

					}


					$loop = new WP_Query( $argsProduct );
				}

				?>
                <div class="protab-ct-item tabPanel free-slide">
                    <div class="container">
						<?php if ( $loop->have_posts() ): ?>
                            <div class="product-bottom-relate d-wrap" data-aos="fade-up">
                                <div class="swiper mySwiper --progressbar">
                                    <div class="swiper-wrapper">
										<?php
										while ( $loop->have_posts() ) {
											$loop->the_post();
											?>
                                            <div class="swiper-slide d-item d-5" data-aos="fade-right" data-aos-delay="	<?php echo $delay; ?>">
												<?php
												/**
												 * GET TEMPLATE PART
												 * BOX HOME PRODUCT
												 */
												$slug = '/partials/loop/box';
												$name = 'home-product';
												echo get_template_part( $slug, $name );
												?>
                                            </div>
											<?php
										}
										wp_reset_query();
										?>
                                    </div>

                                </div>
                                <div class="pagination-con swiper-navi">
                                    <div class="swiper-navigation circle pri prev swiper-abs ">
                                        <i class="fas fa-chevron-left"></i>
                                    </div>
                                    <div class="swiper-navigation circle pri next swiper-abs ">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </div>
						<?php
						else :
							?>
                            <div class="empty-mess">
                                <div class="title-large"><?php echo __( 'Tìm thấy', 'monamedia' ); ?> <span>0</span>
									<?php echo __( 'sản phẩm phù hợp với yêu cầu của bạn', 'monamedia' ); ?>
                                </div>
                                <img alt=""
                                     data-src="<?php echo get_template_directory_uri() ?>/public/images/icons8-google-web-search-64.png"
                                     class="empty-img ls-is-cached lazyloaded"
                                     src="<?php echo get_template_directory_uri() ?>/public/images/icons8-google-web-search-64.png"
                                >
                                <p class="empty-content h5">
									<?php echo __( 'Không tìm thấy kết quả phù hợp với yêu cầu của bạn. Vui lòng thử lại hoặc quay lại trang chủ để xem thêm thông tin. Xin cảm ơn!', 'monamedia' ); ?></p>
                            </div>
						<?php endif ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
				<?php
			} ?>
        </div>
    </div>
	<?php
	}
	?>

