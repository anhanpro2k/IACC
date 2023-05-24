    <!-- footer -->
    <div class="links-main">
        <ul class="links-main-list">
            <li class="links-main-item">
                <a class="link-items" href="">
                    <img src="<?php echo get_site_url() ?>/template/assets/images/vzalo.svg" alt="">
                </a>
            </li>
            <li class="links-main-item">
                <a class="link-items" href="">
                    <img src="<?php echo get_site_url() ?>/template/assets/images/vMess.svg" alt="">
                </a>
            </li>
            <li class="links-main-item">
                <a class="link-items" href="">
                    <img src="<?php echo get_site_url() ?>/template/assets/images/vtiktok.svg" alt="">
                </a>
            </li>
            <li class="links-main-item scroll-to-top">
                <div class="link-items">
                    <span class="items-top" href="">
                        <img src="<?php echo get_site_url() ?>/template/assets/images/arrowTop.svg" alt="">
                        <img src="<?php echo get_site_url() ?>/template/assets/images/arrowTop.svg" alt="">
                    </span>
                </div>
            </li>
        </ul>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-wrap ">
                <div class="footer-content">
                    <div class="footer-box">
                        <div class="footer-box-list d-wrap">
                            <div class="footer-box-item d-item">
                                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_col_1')); ?>
                            </div>
                            <div class="footer-box-item d-item">
                                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_col_2')); ?>
                            </div>
                            <div class="footer-box-item d-item">
                                <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_col_3')); ?>
                            </div>
                            <div class="footer-box-item d-item">
                                <p class="footer-title"><?php echo mona_get_option('footer_title') ?></p>
                                <?php
                                $footer_socials = mona_get_option('footer_socials');
                                if (is_array($footer_socials)) :
                                ?>
                                    <ul class="footer-list list-link mb-32">
                                        <?php foreach ($footer_socials as $item) : if (content_exists($item)) : ?>
                                            <li class="footer-item">
                                                <a href="<?php echo ($item['link']) ? esc_url($item['link']) : 'javascript:;'; ?>" class="footer-link">
                                                    <span class="footer-link-wrap ft-icon">
                                                        <?php var_dump(mona_get_image_id_by_url($item['icon'])); ?>
                                                        <img src="<?php echo get_site_url() ?>/template/assets/images/facebook.svg" alt="">
                                                    </span>
                                                </a>
                                            </li>
                                        <?php endif; endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                <div class="img-bct">
                                    <img src="<?php echo get_site_url() ?>/template/assets/images/imageBCT.png" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-con">
            <div class="container">
                <div class="footer-bottom flex flex-wrap flex-jc-between">
                    <div class="footer-copyright">
                        <p class="text text-center">
                            <span class="img">
                                <img src="<?php echo get_site_url() ?>/template/assets/images/Signature 2.png" alt="">
                            </span>
                        </p>
                    </div>
                    <div class="footer-sc">
                        <p class="text"> @2023</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="back-to-top backToTop">
        <div class="triangle"></div>
        <div class="triangle"></div>
        <div class="triangle"></div>
    </div>

    <script src="<?php echo get_site_url() ?>/template/js/library/jquery/jquery.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/jquery/jquery.easings.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/swiper/swiper-bundle.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/aos/aos.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/select2/select2.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/magnific/jquery.magnific-popup.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/gallery/lightgallery-all.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/countup/jquery.countup.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/countup/jquery.waypoints.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/jquery/jquery-migrate.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/popper/popper.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/plyr/plyr.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/datetime/moment.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/datetime/daterangepicker.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/imageload/imagesloaded.pkgd.min.js "></script>
    <script src="<?php echo get_site_url() ?>/template/js/library/smoothscroll/SmoothScroll.min.js"></script>
    <script src="<?php echo get_site_url() ?>/template/js/main.js" type="module"></script>
    <?php wp_footer(); ?>
    </body>

    </html>