<?php
global $post;
$product = wc_get_product( $post->ID );
?>

<div class="pro-item-wrap">
    <!--    <form id="frmAddProduct">-->
    <input type="hidden" name="product_id" value="<?php echo $post->ID ?>">

    <div class="pro-item-img">
        <a href="<?php echo get_permalink( $post->ID ) ?>" class="img-inner">
			<?php echo get_the_post_thumbnail( $post->ID, 'medium-square' ); ?>
        </a>

        <div class="pro-item-box flex">
            <a href="<?php echo get_permalink( $post->ID ) ?>" class="pro-item-see icon-manu">
                <img src="<?php echo get_site_url(); ?>/template/assets/images/eye-icon.svg"
                     alt="<?php esc_attr_e( 'See product', 'monamedia' ); ?>">
            </a>
            <a href="<?php echo get_permalink( $post->ID ) ?>"
               class="pro-item-pay icon-manu">
                <img src="<?php echo get_site_url(); ?>/template/assets/images/wallet-icon.svg"
                     alt="<?php esc_attr_e( 'Add to cart', 'monamedia' ); ?>">
            </a>
        </div>
    </div>
    <div class="pro-item-content">
        <a href="<?php echo get_permalink( $post->ID ) ?>"
           class="pro-item-name"><?php echo get_the_title( $post->ID ); ?></a>

        <div class="pro-item-bottom flex flex-ai-center flex-jc-between">
            <div class="pro-item-price flex flex-wrap">
				<?php if ( $product->get_sale_price() ) { ?>
                    <span class="pro-item-cur"><?php echo wc_price( $product->get_sale_price() ); ?></span>
                    <span class="pro-item-old"><?php echo wc_price( $product->get_regular_price() ); ?></span>
				<?php } else { ?>
                    <span class="pro-item-cur"><?php echo $product->get_price_html(); ?></span>
				<?php } ?>
            </div>

            <div class="pro-item-check recheck-item">
                <input type="checkbox" name="remove-product[]" value="<?php echo $post->ID ?>" class="recheck-input"
                       hidden>
                <span class="checkbox">
                    <i class="fas fa-check"></i>
                </span>
            </div>
        </div>

    </div>
    <!--    </form>-->
</div>
