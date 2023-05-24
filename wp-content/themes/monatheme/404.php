<?php 
/**
 * The template for displaying 404.
 *
 * @package Monamedia
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
    <!-- Meta
                ================================================== -->
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport"
        content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
    <?php wp_site_icon(); ?>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div class="wrapper">
        <div id="notfound">
            <div class="notfound">
                <div class="notfound-404">
                    <h3><?php echo __( 'Oops! Page not found', 'katen-theme' ) ?></h3>
                    <h1><span>4</span><span>0</span><span>4</span></h1>
                </div>
                <h2><?php echo __( 'we are sorry, but the page you requested was not found', 'katen-theme' ) ?></h2>
                <a href="<?php echo get_site_url() ?>">
                    <?php echo __( 'Back to the home page', 'katen-theme' ) ?>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
