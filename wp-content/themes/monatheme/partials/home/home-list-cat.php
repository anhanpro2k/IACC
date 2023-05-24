<?php
/**
 * Section name: Home List Category (Each section is a category)
 * Description:
 * Author: Monamedia
 * Order: 3
 */
?>

<?php
$mona_home_cat_products = get_field( 'mona_home_cat_products' );
//var_dump( $mona_home_cat_products );
?>

<?php
if ( content_exists( $mona_home_cat_products ) ) {
	?>

	<?php
	foreach ( $mona_home_cat_products as $key_cat => $item_cat ) {
		if ( $item_cat['product_show_type'] ) { ?>
            <div class="home-slide-image load-ele">
                <div class="product-relate free-slide">
                    <div class="container">
                        <div class="product-relate-con">
                            <div class="product-top-relate">
                                <div class="title">
                                    <p class="title-text text-effect">
										<?php echo $item_cat['product_category']->name; ?>
                                    </p>
                                </div>
                                <a href="<?php echo $item_cat['product_link']['url']; ?>" class="link-relate"  data-aos="fade-left">
									<?php echo $item_cat['product_link']['title']; ?>
                                    <span class="icon-relate">
                                             <i class="fas fa-angle-right"></i>
                                         </span>
                                </a>
                            </div>
                            <div class="product-bottom-relate d-wrap"  data-aos="fade-up">
								<?php
								$current_page = get_query_var( 'paged' );
								$current_page = max( 1, $current_page );
								$offset_start = 0;
								$order        = 'DESC';
								$per_page     = 8;
								$offset       = ( $current_page - 1 ) * $per_page + $offset_start;
								$argsProduct  = array(
									'post_type'      => 'product',
									'paged'          => $current_page,
									'offset'         => $offset,
									'post_status'    => 'publish',
									'posts_per_page' => $per_page,
									'order'          => $order,
									'tax_query'      => [
										'relation' => 'AND',
										[
											'taxonomy' => 'product_cat',
											'field'    => 'id',
											'terms'    => $item_cat['product_category']->term_id,
										]
									]
								);
								$loop         = new WP_Query( $argsProduct );
								?>
								<?php if ( $loop->have_posts() ): ?>
                                    <div class="swiper mySwiper">
                                        <div class="swiper-wrapper">
											<?php
											$delay=100;
											while ( $loop->have_posts() ) {
												$loop->the_post();
												?>
                                                <div class="swiper-slide d-item d-5" data-aos="fade-right" data-aos-delay="	<?php echo $delay; ?>">
													<?php
													/**
													 * GET TEMPLATE PART
													 * BOX PRODUCT HOME
													 */
													$slug = '/partials/loop/box';
													$name = 'home-product';
													echo get_template_part( $slug, $name );
													?>
                                                </div>
												<?php
												$delay+=200;
											}
											wp_reset_query();
											?>

                                        </div>

                                    </div>
								<?php
								else :
									?>
                                    <div class="empty-mess">
                                        <div class="title-large"><?php echo __( 'Tìm thấy', 'monamedia' ); ?>
                                            <span>0</span>
											<?php echo __( 'việc làm phù hợp với yêu cầu của bạn', 'monamedia' ); ?>
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
                                <div class="pagination-con swiper-navi">
                                    <div class="swiper-navigation circle pri prev swiper-abs ">
                                        <i class="fas fa-chevron-left"></i>
                                    </div>
                                    <div class="swiper-navigation circle pri next swiper-abs ">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		} else {
			?>
            <div class="product-home-eight load-ele">
                <div class="container">
                    <div class="product-home-eight-con">
                        <div class="product-top-relate">
                            <div class="title">
                                <p class="title-text text-effect">
									<?php echo $item_cat['product_category']->name; ?>
                                </p>
                            </div>
                            <a href="<?php echo $item_cat['product_link']['url']; ?>" class="link-relate" data-aos="fade-left">
								<?php echo $item_cat['product_link']['title']; ?>
                                <span class="icon-relate">
                                             <i class="fas fa-angle-right"></i>
                                         </span>
                            </a>
                        </div>
                        <div class="product-home-eight-content d-wrap"  data-aos="fade-up">
							<?php

							$current_page = get_query_var( 'paged' );
							$current_page = max( 1, $current_page );
							$offset_start = 0;
							$order        = 'DESC';
							$per_page     = 10;
							$offset       = ( $current_page - 1 ) * $per_page + $offset_start;
							$argsProduct  = array(
								'post_type'      => 'product',
								'paged'          => $current_page,
								'offset'         => $offset,
								'post_status'    => 'publish',
								'posts_per_page' => $per_page,
								'order'          => $order,
								'tax_query'      => [
									'relation' => 'AND',
									[
										'taxonomy' => 'product_cat',
										'field'    => 'id',
										'terms'    => $item_cat['product_category']->term_id,
									]
								]
							);
							$loop         = new WP_Query( $argsProduct );
							if ( $loop->found_posts ) {
								?>
								<?php
								while ( $loop->have_posts() ) {
									$loop->the_post();
									?>
                                    <div class="home-eight-item d-item d-5">
										<?php
										/**
										 * GET TEMPLATE PART
										 * BOX PRODUCT HOME
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
								<?php
							} else { ?>
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
								<?php
							} ?>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		} ?>

		<?php
	}
	?>

	<?php
}
?>