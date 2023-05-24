<?php

/**
 * Section name: HOME BANNER
 * Description: Show home banner
 * Author: Monamedia
 * Order: 0
 */
?>
<div class="banner banner-home load-ele">
    <div class="banner-slide">
        <div class="swiper --loop">
            <div class="swiper-wrapper">
                <?php
                // Lấy field
                $mona_home_banner = get_field('mona_home_banner');
                if (content_exists($mona_home_banner)) :
                    foreach ($mona_home_banner as $item) :
                        ?>
                        <div class="swiper-slide">
                            <div class="banner-<?php echo ($item['video_checked']) ? 'video' : 'image'; echo ($item['overlay']) ? '' : ' off-overlay'; ?> banner-media">
                            <?php
                                if ($item['video_checked']) :
                                    if ($item['video_id']) :
                                    ?>
                                    <video playsinline autoplay loop>
                                        <source src="<?php echo $item['video_id']['url'] ?>" type="<?php echo $item['video_id']['mime_type'] ?>">
                                    </video>
                                    <?php
                                    else :
                                        echo '<img src="' . get_template_directory_uri() . '/public/helpers/images/banner-default-min.png" alt="" />';
                                    endif;
                                else:
                                    // Lấy và kiểm tra hình ảnh banner
                                    $desk_img = $item['desk_img'];
                                    $mobile_img = $item['mobile_img'];
                                    if (wp_is_mobile() && !empty($mobile_img)) {
                                        echo wp_get_attachment_image($mobile_img, 'banner-mobile-image');
                                    } else if (!wp_is_mobile() && !empty($desk_img)) {
                                        echo wp_get_attachment_image($desk_img, 'banner-desktop-image');
                                    } else {
                                        echo '<img src="' .  get_template_directory_uri() . '/public/helpers/images/banner-default-min.png" alt="" />';
                                    }
                                endif;
                            ?>
                            </div>
                            <div class="banner-wrap">
                                <div class="container">
                                    <div class="banner-content">
                                        <?php if ( !empty($item['title']) ) : ?>
                                        <div class="t-title-l fw-6 mb-32">
                                            <?php echo $item['title']; ?>
                                        </div>
                                        <?php 
                                        endif;

                                        // check nút xem thêm
                                        $button = $item['button'];
                                        if ( !empty($button['text']) ) :
                                        ?>
                                        <a href="<?php echo (!empty($button['url'])) ? esc_url( $button['url'] ) : 'javascript:;'; ?>" class="btn">
                                            <span class="btn-inner">
                                                <?php echo $button['text']; ?>
                                            </span>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endif; 
                ?>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>