<?php

/**
 * Section name: Breadcrumb
 * Description: Display the breadcrumb after banner of website
 * Author : Monamedia
 */
global $post;
$current = get_queried_object();

$array = [
	[ 'url' => get_the_permalink( MONA_PAGE_HOME ), 'title' => get_the_title( MONA_PAGE_HOME ) ],
];

if ( wp_get_post_parent_id( get_the_ID() ) ) {
	$parentId = wp_get_post_parent_id( get_the_ID() );
	$array[]  = [
		'url'   => get_permalink( $parentId ),
		'title' => get_the_title( $parentId ),
	];
}

if ( is_home() ) {
	if ( isset( $_GET['s'] ) ) {
		$array[] = [
			'url'   => '',
			'title' => __( 'Tìm kiếm', 'monamedia' ),
		];
	} else {
		$array[] = [
			'url'   => '',
			'title' => get_the_title( MONA_PAGE_BLOG ),
		];
	}
} else if ( is_singular( 'post' ) ) {
	$categories = get_the_category();
	$array[]    = [
		'url'   => esc_url( get_category_link( $categories[0]->term_id ) ),
		'title' => $categories[0]->name,
	];

	$array[] = [
		'url'   => '',
		// 'title' => get_the_title($post->ID),
		'title' => __( 'Chi tiết', 'monamedia' )
	];
} else if ( is_product() ) {
	$array[] = [
		'url'   => get_permalink( MONA_WC_PRODUCTS ),
		'title' => get_the_title( MONA_WC_PRODUCTS ),
	];

	$array[] = [
		'url'   => '',
		'title' => get_the_title( $post->ID ),
	];
} else if ( is_category() ) {
	$array[] = [
		'url'   => '',
		'title' => $current->name,
	];
} else if ( is_search() ) {
	$array[] = [
		'url'   => '',
		'title' => __( 'Kết quả tìm kiếm', 'monamedia' ),
	];
} else if ( is_page() ) {
	$array[] = [
		'url'   => '',
		'title' => esc_html( get_the_title( $post->ID ) ),
	];
}
?>

    <!-- BREADCRUMB -->
<?php if ( content_exists( $array ) ) : ?>
    <ul class="breadcrumbs-list">
		<?php
		foreach ( $array as $key => $breadcrumb_item ) :
			$href = $breadcrumb_item['url'];
			$title = $breadcrumb_item['title'];
			$current_breadcrumb = '';
			if ( $breadcrumb_item == end( $array ) ) :
				$href               = 'javascript:;';
				$current_breadcrumb = 'current';
			endif;
			?>
            <li class="breadcrumbs-item <?php echo $current_breadcrumb ?>">
				<?php echo "<a href='$href'>$title</a>" ?>
            </li>
		<?php endforeach; ?>
    </ul>
<?php endif; ?>