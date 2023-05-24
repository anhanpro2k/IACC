<?php

/**
 * The template for displaying single.
 *
 * @package Monamedia
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();
while (have_posts()) :
    the_post();
    mona_set_post_view();
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
        <div class="khuyenmai-ct">
            <div class="container">
                <div class="khuyenmai-con d-wrap">
                    <div class="km-left d-item">
                        <div class="title">
                            <h1 class="title-text">
                                <?php the_title(); ?>
                            </h1>
                        </div>
                        <div class="author">
                            <div class="author-img">
                                <img src="<?php echo get_site_url() ?>/template/assets/images/author.svg" alt="">
                            </div>
                            <p class="author-text">
                                <?php the_author(); ?>
                            </p>
                        </div>
                        <div class="content post-content">
                            <?php the_content(); ?>
                            <!-- share post -->
                            <div class="content-share">
                                <div class="title">
                                    <p class="title-text"><?php echo __('Chia sẻ bài viết', 'monamedia'); ?></p>
                                </div>
                                <div class="content">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>&t=<?php the_title(); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=500'); return false;" class="img-share">
                                        <img src="<?php echo get_site_url() ?>/template/assets/images/facebookCt.svg" alt="">
                                    </a>
                                    <a href="fb-messenger://share/?link=<?php echo urlencode(get_the_permalink()); ?>&app_id=123456789" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=500'); return false;" class="img-share">
                                        <img src="<?php echo get_site_url() ?>/template/assets/images/messengerCt.svg" alt="">
                                    </a>
                                    <a href="http://www.twitter.com/share?url=<?php echo urlencode(get_the_permalink()); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=500'); return false;" class="img-share">
                                        <img src="<?php echo get_site_url() ?>/template/assets/images/twitter.svg" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="km-right d-item">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php
    /**
     * GET TEMPLATE
     * FOOTER META
     */
    $slug = '/partials/global/footer-meta';
    echo get_template_part($slug);
    ?>
    
<?php
endwhile;
get_footer();
