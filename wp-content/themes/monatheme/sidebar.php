<?php

/**
 * The template for displaying sidebar.
 *
 * @package Monamedia
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


if ( ! function_exists('dynamic_sidebar') || ! dynamic_sidebar( 'post_sidebar' ) );

/**
 * GET TEMPLATE
 * Poster quảng cáo
 */
$slug = '/partials/global/poster';
echo get_template_part($slug);