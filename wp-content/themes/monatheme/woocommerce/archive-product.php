<?php
get_header();
$obz = get_queried_object();
?>

    <main class="main">

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
                                    <a href="<?php echo get_permalink( MONA_WC_PRODUCTS ); ?>" class="content-item ">
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


        <div class="product-content-pro">
            <div class="container">
                <div class="product-content-con d-wrap">
                    <div class="con-left d-item">
                        <div class="sidebar mb-24">
                            <div class="side-fixed">
                                <div class="side-fixed-wrap filterJs">
                                    <form id="productListForm" action="">
                                        <input type="hidden" name="sortby" value="">
                                        <div class="sidebar-wrap">
                                            <div class="sidebar-top">
                                                <h2 class="t-white f-20 l-24"><?php echo __( 'Bộ lọc sản phẩm', 'monamedia' ); ?></h2>
                                            </div>
                                            <div class="sidebar-box filterBoxJS">
                                                <div class="sidebar-type filterTypeJS show">
													<?php echo __( 'Danh mục sản phẩm', 'monamedia' ); ?>
                                                    <span class="icon"></span>
                                                </div>
                                                <div class="sidebar-content filter-sub">
                                                    <ul class="cate-list recheck-block">
														<?php
														$args       = array(
															'taxonomy'   => 'product_cat',
															'hide_empty' => false,
														);
														$categories = get_categories( $args );
														foreach ( $categories as $category ) {
															$category_id   = $category->term_id;
															$category_name = $category->name;
															$checked       = isset( $_GET['filter-cat'] ) && $_GET['filter-cat'] == $category_id ? 'checked' : '';
															echo '<li class="cate-item recheck-item"><input type="radio" name="filter-cat" hidden class="recheck-input" value="' . $category_id . '" ' . $checked . '><span class="box"><i class="ti-check"></i></span><span class="text">' . $category_name . '</span></li>';
														}
														?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="sidebar-box filterBoxJS">
                                                <div class="sidebar-type filterTypeJS show">
													<?php echo __( 'Giá', 'monamedia' ); ?>
                                                    <span class="icon"></span>
                                                </div>
                                                <div class="sidebar-content filter-sub">
                                                    <ul class="cate-list recheck-block mb-24">
														<?php
														$price_ranges = array(
															array(
																'name' => '0 - 50.000 đ',
																'min'  => 0,
																'max'  => 50000,
															),
															array(
																'name' => '50.000 - 150.000 đ',
																'min'  => 50000,
																'max'  => 150000,
															),
															array(
																'name' => '150.000 - 250.000 đ',
																'min'  => 150000,
																'max'  => 250000,
															),
															array(
																'name' => 'Trên 250.000 đ',
																'min'  => 250000,
																'max'  => '',
															),
														);
														foreach ( $price_ranges as $price_range ) {
															$name    = $price_range['name'];
															$min     = $price_range['min'];
															$max     = $price_range['max'];
															$checked = '';
															if ( isset( $_GET['price_range'] ) && $_GET['price_range'] == $min . '-' . $max ) {
																$checked = 'checked';
															}
															echo '<li class="cate-item recheck-item"><input type="radio" hidden class="recheck-input" name="price_range" value="' . $min . '-' . $max . '" ' . $checked . '><span class="box"><i class="ti-check"></i></span><span class="text">' . $name . '</span></li>';
														}
														?>
                                                    </ul>


                                                </div>
                                                <button type="submit" class="btn btn-second fw mb-10">
                                                    <span class="btn-inner">
                                                        <?php echo __( 'Lọc sản phẩm', 'monamedia' ); ?>
                                                    </span>
                                                </button>
                                                <button class="btn btn-remove fw btn-fifth t-white">
                                                    <span class="btn-inner">
                                                        <?php echo __( 'Bỏ chọn tất cả', 'monamedia' ); ?>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="side-close">
                                    <i class="fas fa-times close icon"></i>
                                </div>
                            </div>
                            <div class="side-overlay"></div>
                        </div>
						<?php
						/**
						 * GET TEMPLATE
						 * Poster quảng cáo
						 */
						$slug = '/partials/global/poster';
						echo get_template_part( $slug );
						?>
                    </div>
					<?php
					$current_page = get_query_var( 'paged' );
					$current_page = max( 1, $current_page );
					$offset_start = 0;
					$order        = 'DESC';
					$per_page     = 16;
					$offset       = ( $current_page - 1 ) * $per_page + $offset_start;
					$argProducts  = array(
						'post_type'      => 'product',
						'paged'          => $current_page,
						'offset'         => $offset,
						'post_status'    => 'publish',
						'posts_per_page' => $per_page,
						'order'          => $order,
						'meta_query'     => array(
							'relation' => 'AND',
						),
					);

					if ( isset( $_GET['s'] ) ) {
						$argProducts['s'] = esc_html( $_GET['s'] );
					}


					if ( isset( $_GET['filter-cat'] ) ) {
						// Add taxonomy query for product category
						$argProducts['tax_query'][] = array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $_GET['filter-cat'],
						);
					}

					if ( isset( $_GET['price_range'] ) ) {
						// Split price range value into minimum and maximum prices
						$price_range = explode( '-', $_GET['price_range'] );
						$min_price   = (int) $price_range[0];
						$max_price   = ! empty( $price_range[1] ) ? (int) $price_range[1] : '';

						// Add meta query for product price
						$argProducts['meta_query'][] = array(
							'key'     => '_price',
							'value'   => array( $min_price, $max_price ),
							'type'    => 'numeric',
							'compare' => 'BETWEEN',
						);
					}

					if ( isset( $_GET['sortby'] ) ) {
						switch ( $_GET['sortby'] ) {
							case 'newest':
								$argProducts['order'] = 'DESC';
								break;
							case 'price-high-to-low':
								$argProducts['meta_key'] = '_price';
								$argProducts['orderby']  = 'meta_value_num';
								$argProducts['order']    = 'DESC';
								break;
							case 'price-low-to-high':
								$argProducts['meta_key'] = '_price';
								$argProducts['orderby']  = 'meta_value_num';
								$argProducts['order']    = 'ASC';
								break;
							case 'popular':
								$argProducts['orderby']  = 'meta_value_num';
								$argProducts['meta_key'] = '_mona_post_view';
								$argProducts['order']    = 'DESC';
								break;
							default:
								break;
						}
					}

					$loop = new WP_Query( $argProducts );
					?>

                    <div class="con-right d-item">
                        <div class="con-right-top">
                            <div class="box-top">
                                <div class="title-text">
                                    <p>
										<?php echo __( 'Hiển thị', 'monamedia' ); ?>
                                        <strong><?php echo $loop->found_posts; ?></strong> <?php echo __( 'sản phẩm', 'monamedia' ); ?>
										<?php
										if ( isset( $_GET ['s'] ) ) {
											echo __( 'cho từ khóa <strong>"' ) . esc_html( $_GET['s'] ) . '"</strong>';
										}
										?>
                                    </p>
                                </div>
                                <div class="filter-op">
                                    <p class="text"><?php echo __( 'Sắp xếp theo', 'monamedia' ); ?>:</p>
                                    <select class="option-filter" name="sort-by">
                                        <option value="newest" <?php echo isset( $_GET['sortby'] ) && $_GET['sortby'] === 'newest' ? 'selected' : ''; ?>><?php echo __( 'Mới nhất', 'monamedia' ); ?></option>
                                        <option value="popular" <?php echo isset( $_GET['sortby'] ) && $_GET['sortby'] === 'popular' ? 'selected' : ''; ?>><?php echo __( 'Phổ biến nhất', 'monamedia' ); ?></option>
                                        <option value="price-low-to-high" <?php echo isset( $_GET['sortby'] ) && $_GET['sortby'] === 'price-low-to-high' ? 'selected' : ''; ?>><?php echo __( 'Giá từ thấp đến cao', 'monamedia' ); ?></option>
                                        <option value="price-high-to-low" <?php echo isset( $_GET['sortby'] ) && $_GET['sortby'] === 'price-high-to-low' ? 'selected' : ''; ?>><?php echo __( 'Giá từ cao đến thấp', 'monamedia' ); ?></option>
                                    </select>
                                </div>

                                <div class="proFilter-cate-open">
                                    <div class="icon">
                                        <img src="http://schainoffical.monamedia.net/template/assets/images/filter-icon.svg"
                                             alt="">
                                    </div>
                                    <span class="text"><?php echo __( 'Bộ lọc', 'monamedia' ); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="con-right-bottom">
                            <div class="box-bottom d-wrap">
								<?php
								$i = 1;
								while ( $loop->have_posts() ) {
									$loop->the_post();
									?>
                                    <div class="product-content d-item">
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
									<?php
									if ( $i === 10 && $current_page == 1 ) { ?>
										<?php
										$mona_section_product_hot = get_field( 'mona_section_product_hot', MONA_WC_PRODUCTS );
										$product_id_hot           = $mona_section_product_hot;
										?>
										<?php
										if ( ! empty( $mona_section_product_hot ) ) {
											$product_obj = wc_get_product( $product_id_hot );
											?>
                                            <div class="product-content d-item hot">
                                                <div class="product-content-wrap">
													<?php if ( $product_obj->is_on_sale() ) : ?>
														<?php
														$regular_price   = $product_obj->get_regular_price();
														$sale_price      = $product_obj->get_sale_price();
														$sale_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
														?>
                                                        <div class="box-sale">
                                                            <div class="num-sale">
                                                                <p><?php echo $sale_percentage; ?>%</p>
                                                            </div>
                                                            <div class="text-sale">
                                                                <p><?php echo __( 'OFF', 'monamedia' ); ?></p>
                                                            </div>
                                                        </div>
													<?php endif; ?>
                                                    <a href="<?php echo get_permalink( $product_id_hot ) ?>"
                                                       class="pro-img">
														<?php if ( has_post_thumbnail( $product_id_hot ) ) : ?>
															<?php echo get_the_post_thumbnail( $product_id_hot, 'medium' ); ?>
														<?php else : ?>
                                                            <img src="<?php echo get_template_directory_uri(); ?>/public/helpers/images/default-thumbnail.jpg"
                                                                 alt="">
														<?php endif; ?>

                                                        <span class="pro-img-con">
                                                                <img src="<?php echo get_site_url(); ?>/template/assets/images/eye.svg"
                                                                     alt="">
                                                        </span>
                                                    </a>

                                                    <a href="<?php echo get_permalink( $product_id_hot ); ?>"
                                                       class="pro-title">
														<?php echo get_the_title( $product_id_hot ); ?>
                                                    </a>
                                                    <span class="price">
                                                        <?php if ( $product_obj->is_on_sale() ) : ?>
	                                                        <?php if ( $product_obj->get_sale_price() > 0 ) : ?>
                                                                <p class="price-text"><?php echo number_format( floatval( $product_obj->get_sale_price() ), 0, '.', ',' ); ?>đ</p>
	                                                        <?php else: ?>
                                                                <p class="price-text"><?php echo __( 'Liên hệ', 'monamedia' ); ?></p>
	                                                        <?php endif; ?>
                                                            <?php if ( ! empty( $product_obj->get_regular_price() ) ) : ?>
                                                                <p class="price-line">(<?php echo number_format( floatval( $product_obj->get_regular_price() ), 0, '.', ',' ); ?>đ)</p>
	                                                        <?php endif; ?>
                                                        <?php else : ?>
	                                                        <?php if ( ! empty( $product_obj->get_regular_price() ) ) : ?>
                                                                <p class="price-text"><?php echo number_format( floatval( $product_obj->get_regular_price() ), 0, '.', ',' ); ?>đ</p>
	                                                        <?php else : ?>
                                                                <p class="price-text"><?php echo __( 'Liên hệ', 'monamedia' ); ?></p>
	                                                        <?php endif; ?>
                                                        <?php endif; ?>
                                                    </span>
                                                    <div class="btn-hot-product">
                                                        <a href="<?php echo get_permalink( $product_id_hot ); ?>"
                                                           class="btn">
                                                            <span class="btn-inner">
                                                                <span class="text"><?php echo __( 'Xem chi tiết', 'monamedia' ); ?></span>
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
											<?php
										}
										?>

										<?php
									}
									?>
									<?php
									$i ++;
								}
								wp_reset_query();
								?>
                            </div>

                            <div class="prolist-bott flex flex-wrap flex-ai-center flex-jc-center">
								<?php mona_pagination_links( $loop ); ?>
                            </div>
                        </div>
                    </div>
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
get_footer();
?>