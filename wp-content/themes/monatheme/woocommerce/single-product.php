<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
mona_set_post_view();
the_post();
global $post;
$current_product_id = $post->ID;
$product_id         = $post->ID;
$product_obj        = wc_get_product( $current_product_id );
?>
    <main class="main">


        <form id="frmAddProduct" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php the_ID() ?>">
            <input type="hidden" name="wish_product_id" value="<?php echo $product_id; ?>">
            <div class="breadcrumbs other">
                <div class="container">
					<?php
					/**
					 * GET TEMPLATE
					 * BREADCRUMBS
					 */
					$slug = '/partials/breadcrumb';
					echo get_template_part( $slug );
					?>
                </div>
            </div>
            <div class="product-box-list custom-bullet free-slide">
				<?php
				$mona_section_product_category = get_field( 'mona_section_product_category', MONA_WC_PRODUCTS );
				?>
				<?php
				if ( ! empty( $mona_section_product_category ) ) {
					?>
                    <div class="container">
                        <div class="title">
                            <p class="title-text">
								<?php
								if ( ! empty( $mona_section_product_category['category_title'] ) ) {
									echo $mona_section_product_category['category_title'];
								} else {
									echo __( 'Danh mục sản phẩm', 'monamedia' );
								}
								?>
                            </p>
                        </div>
                        <div class="content d-wrap">
                            <div class="swiper mySwiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide d-item d-5">
                                        <a href="<?php echo get_permalink( MONA_WC_PRODUCTS ); ?>"
                                           class="content-item ">
                                      <span class="img-item">
                                          <img src="<?php echo get_site_url(); ?>/template/assets/images/Group 36451.png"
                                               alt="">
                                      </span>
                                            <p class="text">
												<?php echo __( 'Tất cả', 'monamedia' ); ?>
                                            </p>
                                        </a>
                                    </div>
									<?php
									if ( ! empty( $mona_section_product_category['category_list'] ) ) {
										?>
										<?php
										foreach ( $mona_section_product_category['category_list'] as $key_cat => $item_cat ) {
											$cat = get_term( $item_cat, 'product_cat' );
											?>
											<?php
											if ( ! empty( $cat ) ) {
												?>
                                                <div class="swiper-slide d-item d-5">
                                                    <a href="<?php echo get_term_link( $cat ); ?>" class="content-item">
                                                     <span class="img-item">
                                                         <?php
                                                         $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                                                         if ( ! empty( $thumbnail_id ) ) {
	                                                         echo wp_get_attachment_image( $thumbnail_id, 'medium-square', '', [ 'class' => '' ] );
                                                         } else {
	                                                         echo '<img src="' . get_template_directory_uri() . '/public/helpers/images/default-thumbnail.jpg" alt="">';
                                                         }
                                                         ?>
                                                     </span>
                                                        <p class="text">
															<?php echo $cat->name; ?>
                                                        </p>
                                                    </a>
                                                </div>
												<?php
											}
											?>
											<?php
										}
										?>
										<?php
									} ?>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
					<?php
				}
				?>
            </div>

            <div class="prodt section-2">
                <div class="container">
                    <div class="prodt-wrap d-wrap">
                        <div class="prodt-left prodt-slide d-item">
							<?php
							// Get the product ID
							global $product;
							$product_id = $product->get_id();

							// Get the product thumbnail
							$thumbnail_id  = get_post_thumbnail_id( $product_id );
							$thumbnail_url = wp_get_attachment_image_url( $thumbnail_id, 'medium' );

							// Get all images in the product gallery
							$image_ids = $product->get_gallery_image_ids();

							// Display the product gallery if it has images
							if ( $thumbnail_url || ! empty( $image_ids ) ) {
								?>
                                <div class="prodt-main gallery mb-16">
                                    <div class="swiper">
                                        <div class="swiper-wrapper">
											<?php
											// Display the product thumbnail in the gallery
											?>
                                            <div class="swiper-slide">
                                                <div class="prodt-img gItem" data-src="<?php echo $thumbnail_url; ?>">
                                                    <img src="<?php echo $thumbnail_url; ?>" alt="">
                                                </div>
                                            </div>
											<?php
											// Display the remaining images in the product gallery
											foreach ( $image_ids as $image_id ) {
												$image_url = wp_get_attachment_image_url( $image_id, 'medium-square' );
												if ( ! empty( $image_url ) ) {
													?>
                                                    <div class="swiper-slide">
                                                        <div class="prodt-img gItem"
                                                             data-src="<?php echo $image_url; ?>">
                                                            <img src="<?php echo $image_url; ?>" alt="">
                                                        </div>
                                                    </div>
												<?php }
											} ?>
                                        </div>
                                    </div>
                                </div>
								<?php if ( ! empty( $image_ids ) ): ?>
                                    <div class="prodt-thumbs pos-relative">
                                        <div class="swiper">
                                            <div class="swiper-wrapper">
												<?php
												// Display the product thumbnail in the gallery thumbnails
												?>
                                                <div class="swiper-slide">
                                                    <div class="prodt-img">
                                                        <img src="<?php echo $thumbnail_url; ?>" alt="">
                                                    </div>
                                                </div>
												<?php
												// Display the remaining images in the product gallery as thumbnails
												foreach ( $image_ids as $image_id ) {
													$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
													if ( ! empty( $image_url ) ) {
														?>
                                                        <div class="swiper-slide">
                                                            <div class="prodt-img">
                                                                <img src="<?php echo $image_url; ?>" alt="">
                                                            </div>
                                                        </div>
													<?php }
												} ?>
                                            </div>
                                        </div>
                                        <div class="swiper-navigation circle pri prev swiper-abs">
                                            <i class="fas fa-chevron-left"></i>
                                        </div>
                                        <div class="swiper-navigation circle pri next swiper-abs">
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </div>
								<?php endif ?>
							<?php } ?>
                        </div>
                        <div class="prodt-right d-item">
                            <div class="prodt-info-ct">
                                <h1 class="t-title-5 fw-6 mb-8"><?php echo get_the_title( $product_id ) ?></h1>
                                <div class="prodt-cate-list d-wrap mb-16">
                                    <p class="prodt-cate d-item"><?php echo __( 'Thương hiệu', 'monamedia' ); ?>: <span
                                                class="t-second fw-7"><?php echo __( 'In Ảnh Cho
                                        Con', 'monamedia' ); ?></span></p>
                                    <p class="prodt-cate d-item"><?php echo __( 'Tình trạng', 'monamedia' ); ?>:
                                        <span class="t-second fw-7">
                                        <?php
                                        $stock_status = $product_obj->get_stock_status();
                                        if ( $stock_status === 'instock' ) {
	                                        echo __( 'Còn hàng', 'monamedia' );
                                        } elseif ( $stock_status === 'outofstock' ) {
	                                        echo __( 'Hết hàng', 'monamedia' );
                                        } elseif ( $stock_status === 'onbackorder' ) {
	                                        echo __( 'Đang về hàng', 'monamedia' );
                                        }
                                        ?>
                                    </span>
                                    </p>
                                </div>

                                <div class="pro-item-price is-loading-btn flex flex-wrap mb-18">
									<?php if ( $product_obj->is_type( 'variable' ) ) : // Kiểm tra xem sản phẩm có phải là biến thể không ?>
										<?php $variation_min_price = $product_obj->get_variation_price( 'min', true ); // Lấy giá thấp nhất của biến thể ?>
										<?php if ( ! empty( $variation_min_price ) ) : ?>
                                            <span
                                                    class="pro-item-cur"><?php echo number_format( $variation_min_price, 0, '.', ',' ); ?>đ</span>
										<?php else : ?>
                                            <span class="pro-item-cur"><?php echo __( 'Liên hệ', 'monamedia' ); ?></span>
										<?php endif; ?>
										<?php $variation_min_regular_price = $product_obj->get_variation_regular_price( 'min', true ); // Lấy giá thường của biến thể ?>
										<?php if ( ! empty( $variation_min_regular_price ) && $product_obj->is_on_sale() ) : // Kiểm tra xem có khuyến mãi cho biến thể không ?>
                                            <span
                                                    class="pro-item-old">(<?php echo number_format( $variation_min_regular_price, 0, '.', ',' ); ?>đ)</span>
										<?php endif; ?>
									<?php else : // Sản phẩm không phải là biến thể ?>
										<?php if ( $product_obj->is_on_sale() && ! empty( $product_obj->get_sale_price() ) ) : // Kiểm tra xem sản phẩm có khuyến mãi không ?>
                                            <span
                                                    class="pro-item-cur"><?php echo number_format( $product_obj->get_sale_price(), 0, '.', ',' ); ?>đ</span>
											<?php if ( ! empty( $product_obj->get_regular_price() ) ) : ?>
                                                <span
                                                        class="pro-item-old">(<?php echo number_format( $product_obj->get_regular_price(), 0, '.', ',' ); ?>đ)</span>
											<?php endif; ?>
										<?php else : // Sản phẩm không có khuyến mãi ?>
											<?php if ( ! empty( $product_obj->get_regular_price() ) ) : ?>
                                                <span
                                                        class="pro-item-cur"><?php echo number_format( $product_obj->get_regular_price(), 0, '.', ',' ); ?>đ</span>
											<?php else : ?>
                                                <span class="pro-item-cur"><?php echo __( 'Liên hệ', 'monamedia' ); ?></span>
											<?php endif; ?>
										<?php endif; ?>
									<?php endif; ?>

                                </div>

								<?php
								//								 Variation
								$variation = new Variation();
								$attr      = $variation->setProId( get_the_ID() )->html();
								echo $attr;
								?>

                                <!--                            <div class="prodt-info mb-12">-->
                                <!--                                <div class="prodt-info-item">-->
                                <!--                                    <span-->
                                <!--                                        class="prodt-info-tit block">-->
								<?php //echo __( 'Chọn kích thước', 'monamedia' ); ?><!--:</span>-->
                                <!--                                    <div class="prodt-info-ct">-->
                                <!--                                        <div class="prodt-select">-->
                                <!--                                            <select name="" class="select2choose">-->
                                <!--                                                <option value="">6x9cm</option>-->
                                <!--                                                <option value="">6x9cm</option>-->
                                <!--                                                <option value="">6x9cm</option>-->
                                <!--                                            </select>-->
                                <!--                                        </div>-->
                                <!--                                    </div>-->
                                <!--                                </div>-->
                                <!--                                <div class="prodt-info-item">-->
                                <!--                                    <span-->
                                <!--                                        class="prodt-info-tit block">-->
								<?php //echo __( 'Chọn màu sắc', 'monamedia' ); ?><!--:</span>-->
                                <!--                                    <div class="prodt-info-ct">-->
                                <!--                                        <div class="prodt-cl">-->
                                <!--                                            <div class="color-box">-->
                                <!--                                                <div class="color-list flex flex-wrap recheck-block">-->
                                <!--                                                    <div class="color-item recheck-item">-->
                                <!--                                                        <div class="color-item-inner">-->
                                <!--                                                            <input type="radio" class="recheck-input" hidden>-->
                                <!--                                                            <div class="color-item-check" style="background: #E40000">-->
                                <!--                                                                <i class="fas fa-check"></i>-->
                                <!--                                                            </div>-->
                                <!--                                                        </div>-->
                                <!--                                                    </div>-->
                                <!--                                                    <div class="color-item recheck-item">-->
                                <!--                                                        <div class="color-item-inner">-->
                                <!--                                                            <input type="radio" class="recheck-input" hidden>-->
                                <!--                                                            <div class="color-item-check" style="background: #FCD02F;">-->
                                <!--                                                                <i class="fas fa-check"></i>-->
                                <!--                                                            </div>-->
                                <!--                                                        </div>-->
                                <!--                                                    </div>-->
                                <!--                                                    <div class="color-item recheck-item">-->
                                <!--                                                        <div class="color-item-inner">-->
                                <!--                                                            <input type="radio" class="recheck-input" hidden>-->
                                <!--                                                            <div class="color-item-check" style="background: #B7EB8F;">-->
                                <!--                                                                <i class="fas fa-check"></i>-->
                                <!--                                                            </div>-->
                                <!--                                                        </div>-->
                                <!--                                                    </div>-->
                                <!--                                                    <div class="color-item recheck-item">-->
                                <!--                                                        <div class="color-item-inner">-->
                                <!--                                                            <input type="radio" class="recheck-input" hidden>-->
                                <!--                                                            <div class="color-item-check" style="background: #91D5FF;">-->
                                <!--                                                                <i class="fas fa-check"></i>-->
                                <!--                                                            </div>-->
                                <!--                                                        </div>-->
                                <!--                                                    </div>-->
                                <!--                                                    <div class="color-item recheck-item">-->
                                <!--                                                        <div class="color-item-inner">-->
                                <!--                                                            <input type="radio" class="recheck-input" hidden>-->
                                <!--                                                            <div class="color-item-check" style="background: #D3ADF7;">-->
                                <!--                                                                <i class="fas fa-check"></i>-->
                                <!--                                                            </div>-->
                                <!--                                                        </div>-->
                                <!--                                                    </div>-->
                                <!--                                                </div>-->
                                <!--                                            </div>-->
                                <!--                                        </div>-->
                                <!--                                    </div>-->
                                <!--                                </div>-->
                                <!--                                <div class="prodt-info-item">-->
                                <!--                                    <span-->
                                <!--                                        class="prodt-info-tit block">-->
								<?php //echo __( 'Số lượng', 'monamedia' ); ?><!--:</span>-->
                                <!--                                    <div class="prodt-info-ct">-->
                                <!--                                        <div class="qty flex qtyJS">-->
                                <!--                                            <span class="minus icon">-->
                                <!--                                                --->
                                <!--                                            </span>-->
                                <!--                                            <input type="text" class="amount" name="quantity" value="01">-->
                                <!--                                            <span class="plus icon">-->
                                <!--                                                +-->
                                <!--                                            </span>-->
                                <!--                                        </div>-->
                                <!--                                    </div>-->
                                <!--                                </div>-->
                                <!--                            </div>-->

								<?php get_template_part( 'partials/product/upload-media' ) ?>

                                <div class="prodt-note mb-24">
                                    <textarea name="note" id="" cols="30" rows="3" class="prodt-textarea"
                                              placeholder="Ghi chú"></textarea>
                                </div>
                                <div class="prodt-btn-list flex flex-wrap mb-24">
                                    <a href="javascript:;" class="mona-buy-now btn icon-left mb-8 is-loading-btn">
                                    <span class="btn-inner">
                                        <span class="icon">
                                            <img src="<?php echo get_site_url(); ?>/template/assets/images/cart-white.svg"
                                                 alt="">
                                        </span>
                                        <?php echo __( 'Đặt hàng', 'monamedia' ); ?>
                                    </span>
                                    </a>
                                    <a href="javascript:;" class="btn no-bg bd-third t-third icon-left mb-8">
                                        <button type="submit" class="btn-inner">
                                        <span class="icon">
                                            <img src="<?php echo get_site_url(); ?>/template/assets/images/heart-red.svg"
                                                 alt="">
                                        </span>
											<?php
											if ( isset( $_SESSION['wish_list'] ) && in_array( $product_id, $_SESSION['wish_list'] ) ) {
												?>
												<?php echo __( 'Đã thích', 'monamedia' ); ?>
												<?php
											} else { ?>
												<?php echo __( 'Yêu thích', 'monamedia' ); ?>
												<?php
											} ?>
                                        </button>
                                    </a>
                                </div>
                                <div class="prodt-share">
                                    <p class="text"><?php echo __( 'Chia sẻ sản phẩm', 'monamedia' ); ?></p>
                                    <ul class="sc-list">
                                        <li class="sc-item">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_the_permalink() ); ?>&t=<?php the_title(); ?>"
                                               onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=500');return false;"
                                               class="sc-link">
                                                <img src="<?php echo get_site_url(); ?>/template/assets/images/sc-icon-1.svg"
                                                     alt="" rq4k2xpud="">
                                            </a>
                                        </li>
                                        <li class="sc-item">
                                            <a href="http://www.twitter.com/share?url=<?php echo urlencode( get_the_permalink() ); ?>"
                                               onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=500');return false;"
                                               class="sc-link">
                                                <img src="<?php echo get_site_url(); ?>/template/assets/images/sc-icon-2.svg"
                                                     alt="">
                                            </a>
                                        </li>
                                        <li class="sc-item">
                                            <a href="https://plus.google.com/share?url=<?php echo urlencode( get_the_permalink() ); ?>&amp;title=<?php wp_title( '' ) ?>"
                                               onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=500');return false;"
                                               class="sc-link">
                                                <img src="<?php echo get_site_url(); ?>/template/assets/images/sc-icon-4.svg"
                                                     alt="">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-chi-tiet tabJS">
                <div class="product-chi-tiet-top">
                    <div class="container">
                        <div class="product-ct-box">
                            <div class="product-tabs tabBtn">
								<?php echo __( 'Thông tin sản phẩm', 'monamedia' ); ?>
                            </div>

                            <div class="product-tabs tabBtn">
								<?php echo __( 'Thông tin bổ sung', 'monamedia' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tabPanelList">
                    <div class="detail-product tabPanel">
                        <div class="container">
                            <div class="detail-product-con mona-content">
								<?php the_content(); ?>
                            </div>

                        </div>
                    </div>
                    <div class="instruct-image tabPanel">
                        <div class="container">
                            <div class="instruct-image-con mona-content">
								<?php
								$mona_product_more_info = get_field( 'mona_product_more_info' );
								?>
								<?php echo $mona_product_more_info; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-relate other free-slide">
                <div class="container">
                    <div class="product-relate-con">
                        <div class="product-top-relate">
                            <div class="title">
                                <p class="title-text">
									<?php echo __( 'Sản phẩm liên quan', 'monamedia' ); ?>
                                </p>
                            </div>
                            <a href="<?php echo get_permalink( MONA_WC_PRODUCTS ); ?>" class="link-relate">
								<?php echo __( 'Tất cả sản phẩm', 'monamedia' ); ?>
                                <span class="icon-relate">
                                <i class="fas fa-angle-right"></i>
                            </span>
                            </a>
                        </div>
                        <div class="product-bottom-relate d-wrap">
                            <div class="swiper mySwiper">
                                <div class="swiper-wrapper">
									<?php
									$related = wc_get_related_products( get_the_ID(), 6 );
									if ( $related && sizeof( $related ) > 0 ) {
										foreach ( $related as $product_id ) {
											$post = get_post( $product_id );
											setup_postdata( $GLOBALS['post'] =& $post );
											?>
                                            <div class="swiper-slide d-item d-5">
												<?php
												/**
												 * GET TEMPLATE PART
												 * BOX PRODUCT
												 */
												$slug = '/partials/loop/box';
												$name = 'product';
												echo get_template_part( $slug, $name );
												?>
                                            </div>
										<?php }
									}
									wp_reset_postdata();
									?>
                                </div>
                            </div>
                            <div class="pagination-con swiper-navi">
                                <div class="swiper-navigation circle pri prev swiper-abs">
                                    <i class="fas fa-chevron-left"></i>
                                </div>
                                <div class="swiper-navigation circle pri next swiper-abs">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
<?php
get_footer();

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */