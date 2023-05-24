<?php

/**
 * Template name: Sản Phẩm Yêu Thích
 * @author : Trần Phước An
 */
get_header();
while ( have_posts() ) :
	the_post();
	?>

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


    <main class="main">
        <div class="none-cart">
            <div class="container">
                <div class="content-none">
                    <div class="title">
						<?php
						$mona_wishlist_title = get_field( 'mona_wishlist_title' );
						?>
                        <h1 class="title-text">
							<?php
							if ( ! empty( $mona_wishlist_title ) ) {
								echo $mona_wishlist_title;
							} else {
								echo get_the_title();
							} ?>
                        </h1>
                    </div>
					<?php if ( ! empty( $_SESSION['wish_list'] ) ): ?>
                        <div class="whist">
                            <form action="">
                                <div class="genTop d-wrap mb-36 flex-ai-center flex-jc-between">
                                    <div class="genTop-item d-item">
                                        <p class="whist-num fw-6"><?php echo __( 'Tổng cộng', 'monamedia' ); ?> <span
                                                    class="t-second"><?php echo sprintf( "%02d", count( $_SESSION['wish_list'] ) ); ?></span> <?php echo __( 'sản phẩm', 'monamedia' ); ?>
                                        </p>
                                    </div>
                                    <div class="genTop-item d-item">
                                        <button type="submit" class="whist-btn">
											<?php echo __( 'Bỏ thích', 'monamedia' ); ?> (<span class="num">0</span>)
                                        </button>
                                    </div>
                                </div>

                                <div class="pro-list recheck-block d-wrap">
									<?php
									$wish_list = $_SESSION['wish_list'];
									?>
									<?php
									if ( content_exists( $wish_list ) ) {
										?>
										<?php
										foreach ( $wish_list as $key_wish => $item_wish ) {
											$post = get_post( $item_wish, 'product' );
											?>
											<?php
											if ( ! empty( $post ) ) {
												?>
                                                <div class="pro-item d-item d-4">
													<?php
													/**
													 * GET TEMPLATE PART
													 * BOX WISH PRODUCT
													 */
													$slug = '/partials/loop/box';
													$name = 'wish-product';
													echo get_template_part( $slug, $name );
													?>
                                                </div>
												<?php
											}
											?>
											<?php
										}
										?>
										<?php
									}
									?>
                                </div>
                            </form>
                        </div>
					<?php else : ?>
                        <div class="img-none">
                            <img src="<?php echo get_site_url(); ?>/template/assets/images/nonecart.png" alt="">
                        </div>
                        <div class="note">
                            <p class="note-text"><?php echo __( 'Hiện tại bạn không có sản phẩm yêu thích nào', 'monamedia' ); ?>
                                !</p>
                        </div>

                        <a href="<?php echo get_permalink( MONA_WC_PRODUCTS ) ?>" class="btn icon-right">
                        <span class="btn-inner">
                            <span class="text"><?php echo __( 'Mua sắm thêm', 'monamedia' ); ?></span>

                            <span class="icon">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        </span>
                        </a>
					<?php endif ?>
                </div>
            </div>
        </div>
    </main>


	<?php
	/**
	 * GET TEMPLATE
	 * FOOTER META
	 */
	$slug = '/partials/global/footer-meta';
	echo get_template_part( $slug );
	?>

<?php
endwhile;
get_footer();
