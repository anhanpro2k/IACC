<div class="banner-main">
    <div class="container">
        <div class="img-top-left">
            <img src="<?php echo get_site_url() ?>/template/assets/images/vBannerTopLeft.png" alt="">
        </div>
        <div class="img-bottom-left">
            <img src="<?php echo get_site_url() ?>/template/assets/images/imgCham2.png" alt="">
        </div>
        <div class="img-top-right">
            <img src="<?php echo get_site_url() ?>/template/assets/images/imgCham.png" alt="">
        </div>
        <div class="img-bottom-right">
            <img src="<?php echo get_site_url() ?>/template/assets/images/vBottomRight.png" alt="">
        </div>
        <div class="banner-main-con d-wrap">
            <div class="item-left d-2 d-item">
                <?php
                // Lấy field gallery
                $mona_fm_gallery = get_field('mona_fm_gallery', MONA_PAGE_HOME);
                if ($mona_fm_gallery) :
                ?>
                    <div class="item-left-top">
                        <div class="img-item-top">
                            <div class="img-con">
                                <?php echo wp_get_attachment_image($mona_fm_gallery[0], 'medium'); ?>
                            </div>
                        </div>
                        <div class="img-item-top">
                            <div class="img-con">
                                <?php echo wp_get_attachment_image($mona_fm_gallery[1], 'medium'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="item-left-bottom">
                        <div class="img-item-bottom">
                            <div class="img-con">
                                <?php echo wp_get_attachment_image($mona_fm_gallery[2], 'medium'); ?>
                            </div>
                        </div>
                        <div class="img-item-bottom">
                            <div class="img-con">
                                <?php echo wp_get_attachment_image($mona_fm_gallery[3], 'medium'); ?>
                            </div>
                        </div>
                        <div class="img-item-bottom">
                            <div class="img-con">
                                <?php echo wp_get_attachment_image($mona_fm_gallery[4], 'medium'); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="item-right d-2 d-item">
                <div class="title">
                    <p class="title-text">
                        <?php the_field('mona_fm_title', MONA_PAGE_HOME); ?>
                    </p>
                </div>
                <div class="content">
                    <?php the_field('mona_fm_desc', MONA_PAGE_HOME); ?>
                </div>
                <?php
                // Lấy field danh sách
                $mona_fm_list_item = get_field('mona_fm_list_item', MONA_PAGE_HOME);
                if (content_exists($mona_fm_list_item)) :
                ?>
                    <ul class="note">
                        <?php foreach ($mona_fm_list_item as $item) : if ($item) : ?>
                        <li class="note-list">
                            <div class="icon-tik">
                                <i class="far fa-check-circle"></i>
                            </div>
                            <div class="note-text">
                                <p class="text">
                                    <?php echo $item['text']; ?>
                                </p>
                            </div>
                        </li>
                        <?php endif; endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>