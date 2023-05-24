<?php
/**
 * Undocumented function
 * Phân trang link
 * @param string $wp_query
 * @return void
 */
function mona_pagination_links( $wp_query = '' ) {
	if ( $wp_query == '' ) {
	    global $wp_query;
	}
    $bignum = 999999999;
    if ( $wp_query->max_num_pages <= 1 ) {
        return;
    }  
    echo '<div class="paginations">';
    echo paginate_links(
        [
            'base'      => str_replace( $bignum, '%#%', esc_url( get_pagenum_link( $bignum ) ) ),
            'format'    => '',
            'current'   => max( 1, get_query_var( 'paged' ) ),
            'total'     => $wp_query->max_num_pages,
            'prev_text' => '<i class="fa fa-arrow-left" aria-hidden="true"></i>',
            'next_text' => '<i class="fa fa-arrow-right" aria-hidden="true"></i>',
            'type'      => 'list',
            'end_size'  => 2,
            'mid_size'  => 1,
        ]
    );
    echo '</div>';
}

/**
 * Undocumented function
 * Trả về giá trị dạng số
 * @param [type] $value_num
 * @return void
 */
function mona_replace( $value_num = '' ) {
    if ( empty ( $value_num ) ) {
        return;
    }
    $string   = preg_replace( '/\s+/','',$value_num );
    $stringaz = preg_replace( '/[^a-zA-Z0-9_ -]/s', '', $string );
    return $stringaz;
}

/**
 * Undocumented function
 * Trả về format phone
 * @param [type] $hotline
 * @return void
 */
function mona_replace_tel( $hotline = '' ) {
    if ( empty ( $hotline ) ) {
        return;
    }
    $string   = preg_replace( '/\s+/','',$hotline );
    $stringaz = preg_replace( '/[^a-zA-Z0-9_ -]/s', '', $string );
    $tel = 'tel:'.$stringaz;
    return $tel;

}

/**
 * Undocumented function
 * Lấy danh sách cate ids của post
 * @param string $postId
 * @param string $taxonomy
 * @return void
 */
function get_post_term_ids( $postId = '', $taxonomy = 'category' ) {
    global $array_ids;
    if ( $postId == '') {
        $postId = get_the_ID();
    }
    $term_list = wp_get_post_terms( $postId, $taxonomy );
    if ( ! empty ( $term_list ) ) {

        foreach ( $term_list as $item ) {
            $array_ids[] = $item->term_id;
        }
    } else {
        return;
    }
    return $array_ids;
}

/**
 * Undocumented function
 * tạo meta lượt xem cho post
 * @param [type] $postId
 * @return void
 */
function mona_set_post_view( $postId = '' ) {
    if ( empty ( $postId ) ) {
        $postId = get_the_ID();
    }
    $count_key = '_mona_post_view';
    $count = get_post_meta( $postId, $count_key, true );
    if ( $count == '' ) {
        $count = 0;
        delete_post_meta( $postId, $count_key );
        add_post_meta( $postId, $count_key, '0' );
    } else {
        $count++;
        update_post_meta( $postId, $count_key, $count );
    }
}

/**
 * Undocumented function
 * lấy meta lượt xem cho post
 * @param [type] $postId
 * @return void
 */
function mona_get_post_view( $postId = '' ) {
    if ( empty ( $postId ) ) {
        $postId = get_the_ID();
    }
    $count_key = '_mona_post_view';
    $count = get_post_meta( $postId, $count_key, true );
    if ( $count == '' ) {
        delete_post_meta( $postId, $count_key );
        add_post_meta( $postId, $count_key, '0' );
        return 0;
    }

    return $count;

}

/**
 * Undocumented function
 * lấy tiêu đề trang chủ 
 * @return void
 */
function mona_get_home_title() {
    $home_title = get_the_title( get_option('page_on_front') );
    if ( $home_title && $home_title !='' ) {
        $result_title = $home_title;
    } else {
        $result_title = __( 'Trang chủ', 'mona-admin' );
    }
    return $result_title;
}

/**
 * Undocumented function
 * lấy tiêu đề trang blogs
 * @return void
 */
function mona_get_blogs_title() {
    $blogs_title = get_the_title( get_option('page_for_posts', true) );
    if ( $blogs_title && $blogs_title !='' ) {
        $result_title = $blogs_title;
    } else {
        $result_title = __( 'Tin tức', 'mona-admin' );
    }
    return $result_title;
}

/**
 * Undocumented function
 * lấy url trang blogs
 * @return void
 */
function mona_get_blogs_url() {
    $blogs_url = get_the_permalink( get_option( 'page_for_posts', true ) );
    return esc_url( $blogs_url );
}

/**
 * Undocumented function
 * lấy sách các cate cha của cate hiện tại
 * trả về khối html
 * @param [type] $term_id
 * @param [type] $taxonomy
 * @param array $args
 * @return void
 */
function breadcrumb_terms_list_html( $term_id, $taxonomy, $args = array() ) {
    $list = '';
    $term = get_term( $term_id, $taxonomy );
    if ( is_wp_error( $term ) ) {
        return $term;
    }
    if ( ! $term ) {
        return $list;
    }
    $term_id  = $term->term_id;
    $defaults = [
        'format'    => 'name',
        'separator' => '',
        'link'      => true,
        'inclusive' => true,
    ];
    $args = wp_parse_args( $args, $defaults );
    foreach ( array( 'link', 'inclusive' ) as $bool ) {
        $args[ $bool ] = wp_validate_boolean( $args[ $bool ] );
    }
    $parents = get_ancestors( $term_id, $taxonomy, 'taxonomy' );
    if ( $args['inclusive'] ) {
        array_unshift( $parents, $term_id );
    }
    $obz = get_queried_object();
    foreach ( array_reverse( $parents ) as $term_id ) {
        $parent = get_term( $term_id, $taxonomy );
        $name   = ( 'slug' === $args['format'] ) ? $parent->slug : $parent->name;
        if ( $obz->term_id != $term_id && $parent->parent == 0 ) {
            if ( $args['link'] ) {
                $list .= '<li class="breadcrumb-list"><a class="item" href="' . esc_url( get_term_link( $parent->term_id, $taxonomy ) ) . '">' . $name . '</a></li>' . $args['separator'];
            } else {
                $list .= $name . $args['separator'];
            }
        } else {
            $list .= '<li class="breadcrumb-list active"><a class="item" href="' . esc_url( get_term_link( $parent->term_id, $taxonomy ) ) . '">' . $name . '</a></li>' . $args['separator'];
        }
    }
    return $list;
}

/**
 * Undocumented function
 * lấy image id theo url
 * @param [type] $image_url
 * @return void
 */
function mona_get_image_id_by_url( $image_url = '' ) {
    if ( empty ( $image_url ) ) {
        return;
    }
	global $wpdb;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
    if ( ! empty ( $attachment ) ) {
        return $attachment[0];
    }
}

/**
 * Undocumented function
 *
 * @param string $term_id
 * @param string $taxonomy
 * @return boolean
 */
function is_terms_active( $term_id = '', $taxonomy = 'category' ) {
    $termsObj = get_the_terms( get_the_ID(), $taxonomy );
    $count = 0;
    if ( empty( $termsObj ) ) {
        return $count;
    } else {
        foreach ( $termsObj as $key => $item ) {
            if ( $item->term_id === $term_id ) {
                $count++;
            }
        }
    }
    return $count;
}

/**
 * Undocumented function
 *
 * @param string $method
 * @param string $value
 * @return void
 */
function mona_checked( $method = '', $value = '' ) {
    if ( isset( $method ) && is_array( $method ) || is_object( $method ) ) {
        foreach ( $method as $key => $item ) {
            if ( $item === $value ) {
                $checked = "checked='checked'";
                return $checked;
            }
        }
    } elseif ( ! empty ( $method ) && ! is_array( $method ) ) {
        if ( $method === $value ) {
            $checked = "checked='checked'";
            return $checked;
        }
    } else {
        $checked = '';
        return $checked;
    }
}

/**
 * Undocumented function
 * lấy cấp bậc của cate hiện tại
 * @param integer $term_id
 * @param string $taxonomy_type
 * @return void
 */
function _term_get_ancestors_count( $term_id = '', $taxonomy_type = 'category' ) {
    if ( $term_id == '' ) {
        return;
    }
    $ancestors = get_ancestors( $term_id, $taxonomy_type );
    return isset ( $ancestors ) ? (count( $ancestors ) + 1) : 1;
}

/**
 * Undocumented function
 * kiếm trang xem string/array đó có rỗng hay không
 * @param array $content_args
 * @return boolean
 */
function content_exists( $content_args = [] ) {
    if ( ! empty ( $content_args ) ) {
        $done  = 0;
        $total = count( $content_args );
        foreach ( $content_args as $key => $value ) {
            if ( ! is_array( $value ) && $value != '' || is_array( $value ) && content_exists( $value ) ) {
                $done++;
            }
        }
        if ( isset ( $done ) && $done > 0 ) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function show( $args ) {
    if ( get_current_user_id() == 1 ) {
        echo '<pre>';
        print_r ( $args );
        echo '</pre>';
    }
}

function get_attachment_image_markup( $attachment_id, $size = 'thumbnail', $classs = false ) {
    $image_attributes = wp_get_attachment_image_src( $attachment_id, $size );
    $attrClass        = '';
    if ( $image_attributes ) {
        if ( $classs ) {
            $attrClass = 'class="'.esc_attr( $classs ).'"';
        }
        return '<img src="'.$image_attributes[0].'" width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" '.$attrClass.' />';
    }
}

function get_post_image_markup( $post_id, $size = 'post-thumbnail', $classs = false ) {
    $thumbnail_url = get_the_post_thumbnail_url( $post_id, $size );
    $attrClass        = '';
    if ( $thumbnail_url ) {
        if ( $classs ) {
            $attrClass = 'class="'.esc_attr( $classs ).'"';
        }
        return '<img src="'.$thumbnail_url.'" '.$attrClass.' />';
    }
}