<?php

/**
 * The template for displaying category.
 *
 * @package Monamedia
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

get_header();
?>
<div class="breadcrumbs other">
	<div class="container">
		<?php
		/**
		 * GET TEMPLATE
		 * BREADCRUMBS
		 */
		$slug = '/partials/breadcrumb';
		echo get_template_part($slug);
		?>
	</div>
</div>

<main class="main">
	<div class="khuyenmai">
		<div class="container">
			<?php
			if (isset($_GET['s']) && have_posts()) : ?>
				<div class="search-result">
					<h1 class="search-title">
						<?php echo sprintf(__('Kết quả tìm kiếm cho: <span>"%s"</span>', 'monamedia'), esc_html($_GET['s'])); ?>
					</h1>
				</div>
			<?php
			elseif (isset($_GET['s']) && !have_posts()) :
			?>
				<div class="search-result">
					<h1 class="search-title">
						<?php echo sprintf(__('Không tìm thấy bài viết liên quan tới <span>"%s"</span>', 'monamedia'), esc_html($_GET['s'])); ?>
					</h1>
				</div>
			<?php
			else :
				echo '<h1 class="hidden-text">' . get_the_title(MONA_PAGE_BLOG) . '</h1>';
			endif;
			?>

			<div class="khuyenmai-con d-wrap">
				<div class="km-left d-item">
					<div class="content d-wrap">
						<?php
						if (have_posts()) :
							while (have_posts()) : the_post();
								/**
								 * GET TEMPLATE
								 * BOX POST
								 */
								$slug = '/partials/loop/box-post';
								echo get_template_part($slug);
							endwhile;
						endif;
						?>
					</div>
					<div class="prolist-bott flex flex-wrap flex-ai-center flex-jc-center">
						<?php mona_pagination_links(); ?>
					</div>
				</div>

				<div class="km-right d-item">
					<div class="km-right-search">
						<form method="get" id="searchform" class="searchform" action="<?php echo get_permalink(MONA_PAGE_BLOG); ?>">
							<input type="search" class="input-search" name="s" value="<?php echo get_search_query(); ?>" id="s" 
							  placeholder="<?php echo esc_attr_x('Tìm kiếm mã khuyến mãi', 'placeholder', 'monamedia'); ?>" />
							<button type="submit" class="btn-search">
								<span><i class="fas fa-search"></i></span>
							</button>
						</form>
					</div>
					<?php get_sidebar(); ?>
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
	echo get_template_part($slug);
	?>
</main>
<?php get_footer();
