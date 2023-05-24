<div class="poster">
    <div class="img-poster">
        <?php
        $img = mona_get_image_id_by_url(mona_get_option('section_poster_img'));
        if ($img) {
            echo wp_get_attachment_image($img, 'medium-square');
        } else {
            echo '<img src="' .  get_template_directory_uri() . '/public/helpers/images/banner-default-min.png" alt="" />';
        }
        ?>
    </div>
    <div class="poster-content">
        <div class="content-pt">
            <div class="content-text">
                <?php echo mona_get_option('section_poster_desc'); ?>
            </div>
        </div>
        <?php
        $btntext = mona_get_option('section_poster_btntext');
        $btnurl = mona_get_option('section_poster_btnurl');
        if ($btntext) :
        ?>
            <a href="<?php echo ($btnurl) ? esc_url($btnurl) : 'javascript:;'; ?>" class="btn">
                <span class="btn-inner">
                    <span class="text"><?php echo $btntext; ?></span>
                </span>
            </a>
        <?php endif; ?>
    </div>
</div>