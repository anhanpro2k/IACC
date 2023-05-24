<?php

/**
 * Template name: Liên hệ
 * @author : Trần Phước An
 */
get_header();
while (have_posts()) :
    the_post();
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
        <div class="contact">
            <div class="container">
                <div class="contact-title">
                    <h1 class="text">
                        <?php echo (get_field('mona_contact_title')) ? get_field('mona_contact_title') : get_the_title(); ?>
                    </h1>
                </div>
                <div class="content d-wrap">
                    <div class="contact-left d-item">
                        <div class="title">
                            <div class="title-text">
                                <?php the_field('mona_contact_subtitle'); ?>
                            </div>
                        </div>
                        <?php
                        $mona_contact_info_item = get_field('mona_contact_info_item');
                        if (content_exists($mona_contact_info_item)) :
                        ?>
                            <ul class="list">
                                <?php foreach ($mona_contact_info_item as $item) : if (content_exists($item)) : ?>
                                    <li class="item-con">
                                        <a href="<?php echo ($item['url']) ? esc_url($item['url']) : 'javascript:;'; ?>" class="item">
                                            <div class="img-item">
                                                <?php 
                                                if ($item['icon']) :
                                                    echo wp_get_attachment_image($item['icon'], 'icon');
                                                else :
                                                    echo '<img src="' . get_site_url() . '/template/assets/images/location.svg" alt="">';
                                                endif; ?>
                                            </div>
                                            <div class="content-item">
                                                <p class="title"><?php echo $item['title']; ?></p>
                                                <p class="text"><?php echo $item['desc']; ?></p>
                                            </div>
                                        </a>
                                    </li>
                                <?php endif; endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="contact-right d-item">
                        <div class="con-right">
                            <div class="title">
                                <p class="title-text">
                                    <?php the_field('mona_contact_form_title'); ?>
                                </p>
                            </div>
                            <div class="form-lien-he">
                                <?php
                                // kiểm tra shortcode
                                $mona_contact_form_shortcode = get_field('mona_contact_form_shortcode');
                                $mona_contact_form_doshortcode = do_shortcode($mona_contact_form_shortcode);
                                echo ($mona_contact_form_doshortcode != $mona_contact_form_shortcode) ? $mona_contact_form_doshortcode : "";
                                ?>
                            </div>
                        </div>
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
