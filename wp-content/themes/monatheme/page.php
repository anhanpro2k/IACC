<?php

/**
 * The template for displaying page template.
 *
 * @package Monamedia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
while ( have_posts() ) :
	the_post();
	mona_set_post_view();
	?>
	<?php
	if ( is_page( MONA_WC_CHECKOUT ) ) { ?>
        <main class="main">
            <div class="success-product">
                <div class="bg-success">
                    <img src="<?php echo get_site_url(); ?>/template/assets/images/bnsuccess.png" alt="">
                </div>
                <div class="success-product-content">
                    <div class="container">
						<?php the_content(); ?>
                    </div>
                </div>
            </div>
			<?php
			/**
			 * GET TEMPLATE
			 * FOOTER META
			 */
			$slug = '/partials/global/footer-meta';
			echo get_template_part( $slug );
			?>
        </main>
		<?php
	} else if ( is_page( MONA_WC_CART ) ) { ?>
        <main class="main">
            <div class="success-product">
                <div class="bg-success">
                    <img src="<?php echo get_site_url(); ?>/template/assets/images/bnsuccess.png" alt="">
                </div>
                <div class="success-product-content">
                    <div class="container">
						<?php the_content(); ?>
                    </div>
                </div>
            </div>
			<?php
			/**
			 * GET TEMPLATE
			 * FOOTER META
			 */
			$slug = '/partials/global/footer-meta';
			echo get_template_part( $slug );
			?>
        </main>
		<?php
	} else {
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
            <div class="chinh-sach">
                <div class="container">
                    <div class="chinh-sach-con d-wrap">
                        <div class="chinh-sach-left d-item">
							<?php
							/**
							 * GET TEMPLATE
							 * Poster quảng cáo
							 */
							$slug = '/partials/global/poster';
							echo get_template_part( $slug );
							?>
                        </div>
                        <div class="chinh-sach-right d-item">
                            <div class="title">
                                <h1 class="title-text">
									<?php echo get_the_title(); ?>
                                </h1>
                            </div>
                            <div class="box-item">
                                <div class="date">
                                    <div class="date-img">
                                        <img src="<?php echo get_site_url() ?>/template/assets/images/date.svg" alt="">
                                    </div>
                                    <p class="date-text"><?php the_date( '\N\gà\y j \t\há\n\g n \nă\m Y' ) ?></p>
                                </div>
                                <div class="author">
                                    <div class="author-img">
                                        <img src="<?php echo get_site_url() ?>/template/assets/images/author.svg"
                                             alt="">
                                    </div>
                                    <p class="author-text">
										<?php the_author(); ?>
                                    </p>
                                </div>
                                <div class="view">
                                    <div class="view-img">
                                        <img src="<?php echo get_site_url() ?>/template/assets/images/eyes.svg" alt="">
                                    </div>
                                    <p class="view-text">
										<?php
										$views = mona_get_post_view();
										if ( is_numeric( $views ) ) {
											if ( $views < 999 ) {
												$views_format = $views;
											} else {
												$views_format = number_format( round( $views / 1000, 1 ), 1 ) . "k";
											}
											echo $views_format . ' ' . __( 'Người xem', 'monamedia' );
										}
										?>
                                    </p>
                                </div>
                            </div>

                            <div class="content post-content">
								<?php the_content(); ?>
                            </div>

                            <div class="box-question">
                                <h3 class="box-title"><?php echo __( 'Bài viết có hữu ích cho bạn không?', 'monamedia' ); ?></h3>
                                <div class="box-btn">
                                    <a href="javascript:;" class="btn icon-left no-bg bd-second t-second">
                                    <span class="btn-inner">
                                        <span class="icon">
                                            <img src="<?php echo get_site_url() ?>/template/assets/images/like.svg"
                                                 alt="">
                                        </span>
                                        <span class="text"><?php echo __( 'Có', 'monamedia' ); ?></span>
                                    </span>
                                    </a>
                                    <a href="javascript:;" class="btn icon-left no-bg bd-second t-second">
                                    <span class="btn-inner">
                                        <span class="icon">
                                            <img src="<?php echo get_site_url() ?>/template/assets/images/dislike.svg"
                                                 alt="">
                                        </span>
                                        <span class="text"><?php echo __( 'Không', 'monamedia' ); ?></span>
                                    </span>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php
			// kiểm tra section thông tin thêm
			$mona_section_infor = get_field( 'mona_section_infor' );
			if ( content_exists( $mona_section_infor ) ) :
				?>
                <div class="banner-cs">
                    <div class="container">
                        <div class="img-left">
                            <img src="<?php echo get_site_url() ?>/template/assets/images/imgCham.png" alt="">
                        </div>
                        <div class="img-right">
                            <img src="<?php echo get_site_url() ?>/template/assets/images/imgCham.png" alt="">
                        </div>
                        <div class="img-people">
							<?php echo wp_get_attachment_image( $mona_section_infor['img'], 'medium-square' ); ?>
                        </div>
                        <div class="content">
                            <div class="title">
                                <p class="title-text">
									<?php echo $mona_section_infor['title']; ?>
                                </p>
                            </div>
                            <div class="box-btn">
                                <a href="tel:<?php echo preg_replace( '/\D/', '', $mona_section_infor['phone'] ); ?>"
                                   class="btn icon-left no-bg bd-second t-second other-hv">
                                <span class="btn-inner">
                                    <span class="icon">
                                        <img src="<?php echo get_site_url() ?>/template/assets/images/phone.svg" alt="">
                                    </span>
                                    <span class="text"><?php echo $mona_section_infor['phone']; ?></span>
                                </span>
                                </a>
                                <a href="mailto:<?php echo $mona_section_infor['email']; ?>"
                                   class="btn icon-left other-hv">
                                <span class="btn-inner">
                                    <span class="icon">
                                        <img src="<?php echo get_site_url() ?>/template/assets/images/mail-01.svg"
                                             alt="">
                                    </span>
                                    <span class="text"><?php echo $mona_section_infor['email']; ?></span>
                                </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
        </main>
		<?php
	} ?>
<?php
endwhile;
get_footer();
