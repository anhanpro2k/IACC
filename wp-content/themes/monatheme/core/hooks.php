<?php
/**
 * Undocumented function
 *
 * @return void
 */
function add_after_setup_theme() {
    // regsiter menu
    register_nav_menus(
        [
            'main-menu' => __( 'Menu chính', 'monamedia' ),
            'mobile-menu' => __( 'Menu mobile', 'monamedia' ),
            'pro-cat-menu' => __( 'Menu Danh mục sản phẩm', 'monamedia' ),
        ]
    );
    // add size image
    add_image_size( 'banner-desktop-image', 1920, 790, false );
    add_image_size( 'banner-mobile-image', 400, 675, false );
    add_image_size( 'medium-square', 500, 500, false );
    add_image_size( 'small-square', 150, 150, false );
    add_image_size( 'icon', 50, 50, false );
    
}
add_action( 'after_setup_theme', 'add_after_setup_theme' );

/**
 * Undocumented function
 *
 * @return void
 */
function mona_add_styles_scripts() {
    wp_enqueue_style( 'mona-custom', get_template_directory_uri() . '/public/helpers/css/mona-custom.css', array(), rand() );
    wp_enqueue_script( 'mona-frontend', get_template_directory_uri() . '/public/helpers/scripts/mona-frontend.js', array(), false, true );
    wp_localize_script('mona-frontend', 'mona_ajax_url', 
        [
            'ajaxURL' => admin_url('admin-ajax.php'),
            'siteURL' => get_site_url(),
        ]
    );
}
add_action( 'wp_enqueue_scripts', 'mona_add_styles_scripts' );

/**
 * Undocumented function
 *
 * @param [type] $tag
 * @param [type] $handle
 * @param [type] $src
 * @return void
 */
function mona_add_module_to_my_script( $tag, $handle, $src ) {
    if ( 'mona-frontend' === $handle ) {
        $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'mona_add_module_to_my_script', 10, 3 );

/**
 * Undocumented function
 *
 * @return void
 */
function mona_redirect_external_after_logout() {
    wp_redirect( get_the_permalink( MONA_PAGE_HOME ) );
    exit();
}
//add_action( 'wp_logout', 'mona_redirect_external_after_logout' );

/**
 * Undocumented function
 *
 * @param [type] $query
 * @return void
 */
function mona_parse_request_post_type( $query ) {
    if ( ! is_admin() ) {
        $query->set('ignore_sticky_posts', true);
        $ptype = $query->get('post_type', true);
        $ptype = (array) $ptype;

        // if ( isset( $_GET['s'] ) ) {
        //     $ptype[] = 'post';
        //     $query->set('post_type', $ptype);
        //     $query->set( 'posts_per_page' , 12);
        // }

        // if ( $query->is_main_query() && $query->is_tax( 'category_library' ) ) {
        //     $ptype[] = 'mona_library';
        //     $query->set('post_type', $ptype);
        //     $query->set('posts_per_page', 12);
        // }

    }
    return $query;
}
add_filter( 'pre_get_posts', 'mona_parse_request_post_type' );

/**
 * Undocumented function
 *
 * @return void
 */
function mona_register_sidebars() {
    register_sidebar( 
        [
            'id'            => 'footer_col_1',
            'name'          => __( 'Footer 1', 'mona-admin' ),
            'description'   => __( 'Nội dung widget.', 'mona-admin' ),
            'before_widget' => '<div id="%1$s" class="%2$s footer-text">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="footer-title">',
            'after_title'   => '</p>',
        ]
    );
    register_sidebar( 
        [
            'id'            => 'footer_col_2',
            'name'          => __( 'Footer 2', 'mona-admin' ),
            'description'   => __( 'Nội dung widget.', 'mona-admin' ),
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="footer-title">',
            'after_title'   => '</p>',
        ]
    );
    register_sidebar( 
        [
            'id'            => 'footer_col_3',
            'name'          => __( 'Footer 3', 'mona-admin' ),
            'description'   => __( 'Nội dung widget.', 'mona-admin' ),
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="footer-title">',
            'after_title'   => '</p>',
        ]
    );
    register_sidebar( 
        [
            'id'            => 'post_sidebar',
            'name'          => __( 'Post sidebar', 'mona-admin' ),
            'description'   => __( 'Nội dung widget.', 'mona-admin' ),
            'before_widget' => '<div id="%1$s" class="widget km-right-top %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="title"><p class="title-text">',
            'after_title'   => '</p></div>',
        ]
    );
}
add_action( 'widgets_init', 'mona_register_sidebars' );

/**
 * Undocumented function
 *
 * @param [type] $post_states
 * @param [type] $post
 * @return void
 */
function mona_add_post_state( $post_states, $post ) {
    if ( $post->ID == MONA_PAGE_HOME ) {
        $post_states[] = __( 'PAGE - Trang chủ', 'mona-admin' );
    }
    if ( $post->ID == MONA_PAGE_BLOG ) {
        $post_states[] = __( 'PAGE - Trang Tin tức', 'mona-admin' );
    }
    return $post_states;
}
add_filter( 'display_post_states', 'mona_add_post_state', 10, 2 );

/**
 * Undocumented function
 *
 * @param [type] $html
 * @return void
 */
function mona_change_logo_class( $html ) {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $html           = sprintf( '<a href="%1$s" class="header-icon" rel="home" itemprop="url"><div class="icon">%2$s</div></a>',
        esc_url( home_url() ),
        wp_get_attachment_image( $custom_logo_id, 'full', false, 
            [
                'class'  => 'header-logo-image',
            ]
        )
    );
    return $html;  
}
//add_filter( 'get_custom_logo', 'mona_change_logo_class' );

/**
 * Undocumented function
 *
 * @param [type] $url
 * @param [type] $path
 * @param [type] $blog_id
 * @return void
 */
function mona_filter_admin_url( $url, $path, $blog_id ) {
    if ( $path === 'admin-ajax.php' && ! is_admin() ){
        $url.='?mona-ajax';
    }
    return $url;
}
add_filter( 'admin_url', 'mona_filter_admin_url', 999, 3 );