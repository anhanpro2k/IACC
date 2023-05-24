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
			<h1 class="hidden-text"><?php echo get_queried_object()->name; ?></h1>
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
					<?php
					/**
					 * GET TEMPLATE
					 * Search form & sidebar
					 */
					echo get_template_part('searchform');

					get_sidebar(); ?>
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
