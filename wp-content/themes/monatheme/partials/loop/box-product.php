<?php
global $post;
$product = wc_get_product( $post->ID );

?>

<div class="product-content-wrap">
    <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" class="pro-img">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php echo get_the_post_thumbnail( $product->get_id(), 'medium' ); ?>
		<?php else : ?>
            <img src="<?php echo get_template_directory_uri(); ?>/public/helpers/images/default-thumbnail.jpg" alt="">
		<?php endif; ?>

        <span class="pro-img-con">
            <img src="<?php echo get_site_url(); ?>/template/assets/images/eye.svg" alt="">
        </span>
    </a>
    <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" class="pro-title">
		<?php echo esc_html( $product->get_name() ); ?>
    </a>
    <span class="price">
   <?php if ( $product->is_type( 'variable' ) ) : // Kiểm tra xem sản phẩm có phải là biến thể không ?>
	   <?php $variation_min_price = $product->get_variation_price( 'min', true ); // Lấy giá thấp nhất của biến thể ?>
	   <?php if ( ! empty( $variation_min_price ) ) : ?>
           <p class="price-text"><?php echo number_format( $variation_min_price, 0, '.', ',' ); ?>đ</p>
	   <?php else : ?>
           <p class="price-text"><?php echo __( 'Liên hệ', 'monamedia' ); ?></p>
	   <?php endif; ?>
        <?php $variation_min_regular_price = $product->get_variation_regular_price( 'min', true ); // Lấy giá thường của biến thể ?>
        <?php if ( ! empty( $variation_min_regular_price ) && $product->is_on_sale() ) : // Kiểm tra xem có khuyến mãi cho biến thể không ?>
           <p class="price-line"><?php echo number_format( $variation_min_regular_price, 0, '.', ',' ); ?>đ</p>
	   <?php endif; ?>
   <?php else : // Sản phẩm không phải là biến thể ?>
	   <?php if ( $product->is_on_sale() && ! empty( $product->get_sale_price() ) ) : // Kiểm tra xem sản phẩm có khuyến mãi không ?>
           <p class="price-text"><?php echo number_format( $product->get_sale_price(), 0, '.', ',' ); ?>đ</p>
            <?php if ( ! empty( $product->get_regular_price() ) ) : ?>
               <p class="price-line"><?php echo number_format( $product->get_regular_price(), 0, '.', ',' ); ?>đ</p>
		   <?php endif; ?>
	   <?php else : // Sản phẩm không có khuyến mãi ?>
		   <?php if ( ! empty( $product->get_regular_price() ) ) : ?>
               <p class="price-text"><?php echo number_format( $product->get_regular_price(), 0, '.', ',' ); ?>đ</p>
		   <?php else : ?>
               <p class="price-text"><?php echo __( 'Liên hệ', 'monamedia' ); ?></p>
		   <?php endif; ?>
	   <?php endif; ?>
   <?php endif; ?>
</span>

</div>
