<?php

/**
 * The template for displaying header.
 *
 * @package Monamedia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
    <!-- Meta
                ================================================== -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport"
          content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
	<?php wp_site_icon(); ?>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>
    <link rel="stylesheet" href="<?php echo get_site_url() ?>/template/css/style.css">
    <link rel="stylesheet" href="<?php echo get_site_url() ?>/template/css/backdoor.css"/>
    <link rel="icon" type="image/png" href="<?php echo get_site_url() ?>/template/assets/images/mona.png"/>
	<?php wp_head(); ?>
</head>
<?php
if ( wp_is_mobile() ) {
	$body = 'mobile-detect';
} else {
	$body = 'desktop-detect';
}
?>

<body <?php body_class( $body ); ?>>
<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="menu-top">
                <a href="<?php echo get_home_url(); ?>" class="header-logo">
                        <span class="logo-img">
                            <?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'small-square' ); ?>
                        </span>
                    <div class="header-slogan">
						<?php echo get_bloginfo( 'name' ) . '<br/>' . get_bloginfo( 'description' ); ?>
                    </div>
                </a>
                <div class="header-search">
                    <div class="form-search">
                        <form method="get" action="<?php echo get_permalink( MONA_WC_PRODUCTS ); ?>">
                            <div class="form-wrap flex">
                                <input type="search" class="input" name="s" value="<?php echo get_search_query(); ?>"
                                       id="s"
                                       placeholder="<?php echo esc_attr_x( 'Tìm kiếm sản phẩm...', 'placeholder', 'monamedia' ); ?>"/>
                                <input class="btn-search" type="submit" value="">
                            </div>
                        </form>
                    </div>
                </div>
				<?php
				$header_address_desc = mona_get_option( 'header_address_desc' );
				$header_address      = mona_get_option( 'header_address' );

				if ( $header_address_desc || $header_address ) :
					?>
                    <div class="header-addr">
                        <div class="icon">
                            <img src="<?php echo get_site_url() ?>/template/assets/images/marker-pin.svg" alt="">
                        </div>
                        <div class="header-addr-ct pos-relative">
                            <p class="tit">
                                <span class="tit-text"><?php echo ( $header_address_desc ) ? $header_address_desc : $header_address; ?></span>
                                <span class="icon">
                                        <img src="<?php echo get_site_url() ?>/template/assets/images/arr-down.svg"
                                             alt="">
                                    </span>
                            </p>
							<?php if ( $header_address && $header_address_desc ) : ?>
                                <p class="addr-ct pos-abs"><?php echo $header_address; ?></p>
                                <p class="addr"><?php echo $header_address; ?></p>
							<?php endif; ?>
                        </div>
                    </div>
				<?php endif; ?>
				<?php


				if ( isset( $_GET['wish_product_id'] ) ) {
					$product_id = $_GET['wish_product_id'];
					if ( ! isset( $_SESSION['wish_list'] ) ) {
						$_SESSION['wish_list'] = array();
					}
					if ( ! in_array( $product_id, $_SESSION['wish_list'] ) ) {
						$_SESSION['wish_list'][] = $product_id;
					}
				}


				if ( isset( $_GET['remove-product'] ) ) {
					$remove_ids = $_GET['remove-product']; // Get the product IDs to remove from the URL parameter
					$wish_list  = $_SESSION['wish_list']; // Get the current wish list from the session

					// Loop through the product IDs to remove
					foreach ( $remove_ids as $remove_id ) {
						// Find the index of the product ID to remove in the wish list
						$index = array_search( $remove_id, $wish_list );

						// If the product ID exists in the wish list, remove it
						if ( $index !== false ) {
							unset( $wish_list[ $index ] );
						}
					}
					// Update the wish list in the session
					$_SESSION['wish_list'] = $wish_list;
				}
				?>
                <div class="header-cart">
                    <div class="icon">
                        <a href="<?php echo get_permalink( MONA_PAGE_WISHLIST ) ?>">
                            <span class="num"><?php echo isset( $_SESSION['wish_list'] ) !== null && ! empty( $_SESSION['wish_list'] ) ? count( $_SESSION['wish_list'] ) : 0; ?></span>
                            <img src="<?php echo get_site_url(); ?>/template/assets/images/cart-icon.svg" alt="">
                        </a>
                    </div>
                    <a href="<?php echo get_permalink( MONA_PAGE_WISHLIST ) ?>">
                        <span class="text"><?php echo __( 'Yêu thích', 'monamedia' ); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <div class="header-wrap flex flex-ai-center flex-jc-between">
                <div class="header-btn">
                    <div class="toggle toggle-header">
                        <div class="toggle-inner">
                                <span class="toggle-item">
                                    <img src="<?php echo get_site_url() ?>/template/assets/images/menu-hd.svg" alt="">
                                </span>
                            <span class="toggle-item">
                                    <img src="<?php echo get_site_url() ?>/template/assets/images/close-hd.svg" alt="">
                                </span>
                        </div>
                    </div>
                    <span class="toggle-text"><?php echo __( 'Danh mục sản phẩm', 'monamedia' ); ?><span class="icon"><i
                                    class="fas fa-chevron-down"></i></span></span>
                </div>
                <div class="header-menu">
                    <div class="menu-nav">
						<?php
						/**
						 * GET MAIN MENU
						 */
						wp_nav_menu( array(
							'container'       => false,
							'container_class' => '',
							'menu_class'      => 'menu-list',
							'theme_location'  => 'main-menu',
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'fallback_cb'     => false,
							'walker'          => new Mona_Walker_Nav_Menu,
						) );
						?>
                    </div>
                </div>
                <div class="header-logo">
                    <a href="<?php echo get_home_url(); ?>">
						<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'icon' ); ?>
                    </a>
                </div>
                <div class="header-box">
                    <div class="header-search">
                            <span class="icon">
                                <img src="<?php echo get_site_url() ?>/template/assets/images/search.svg" alt="">
                            </span>

                        <div class="form-search">
                            <form action="<?php echo get_permalink( MONA_WC_PRODUCTS ) ?>">
                                <div class="form-wrap flex">
                                    <input class="btn-search" type="submit" value="">
                                    <input name="s" value="<?php echo get_search_query(); ?>"
                                           id="s" type="text" class="input"
                                           placeholder="<?php echo __( 'Bạn đang tìm gì', 'monamedia' ); ?>?">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="header-cart">
                        <div class="icon">
                            <a href="<?php echo get_permalink( MONA_PAGE_WISHLIST ) ?>">
                                <span class="num"><?php echo isset( $_SESSION['wish_list'] ) !== null && ! empty( $_SESSION['wish_list'] ) ? count( $_SESSION['wish_list'] ) : 0; ?></span>
                                <img src="<?php echo get_site_url(); ?>/template/assets/images/cart-icon.svg" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</header>

<div class="header-ct">
    <div class="header-menu-desk">
        <div class="menu-nav">
			<?php
			/**
			 * GET PRODUCT CATEGORY MENU
			 */
			wp_nav_menu( array(
				'container'       => false,
				'container_class' => '',
				'menu_class'      => 'menu-list',
				'theme_location'  => 'pro-cat-menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'fallback_cb'     => false,
				'walker'          => new Mona_Walker_Nav_Menu,
			) );
			?>
        </div>
    </div>
    <div class="header-menu">
        <div class="header-nav flex flex-jc-between flex-ai-center">
            <div class="menu-nav">
				<?php
				/**
				 * GET MOBILE MENU
				 */
				wp_nav_menu( array(
					'container'       => false,
					'container_class' => '',
					'menu_class'      => 'menu-list',
					'theme_location'  => 'mobile-menu',
					'before'          => '',
					'after'           => '',
					'link_before'     => '',
					'link_after'      => '',
					'fallback_cb'     => false,
					'walker'          => new Mona_Walker_Nav_Menu,
				) );
				?>
            </div>

			<?php if ( $header_address_desc && $header_address ) : ?>
                <div class="header-addr container mb">
                    <div class="header-addr-ct">
                        <p class="tit-text"><?php echo $header_address_desc; ?></p>
                        <p class="tit">
                                <span class="icon">
                                    <img src="<?php echo get_site_url() ?>/template/assets/images/marker-pin.svg"
                                         alt="">
                                </span>
                            <span class="addr"><?php echo $header_address; ?></span>
                        </p>
                    </div>
                </div>
			<?php endif; ?>
        </div>
    </div>

</div>

<div class="bg-fade hd-bg"></div>