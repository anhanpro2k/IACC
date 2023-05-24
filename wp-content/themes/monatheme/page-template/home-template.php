<?php
/**
 * Template name: Trang chủ
 * @author : Trần Phước An
 */
get_header();
while ( have_posts() ):
	the_post();
	?>
    <main>
		<?php
		Elements::Group( 'home' )->Html();
		?>
		<?php
		/**
		 * GET TEMPLATE PART
		 * Footer Meta
		 */
		$slug = '/partials/global/footer-meta';
		echo get_template_part( $slug );
		?>
    </main>
<?php
endwhile;
get_footer();