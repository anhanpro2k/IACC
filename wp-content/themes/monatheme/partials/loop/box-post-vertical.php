<div class="content-con">
    <div class="img-item">
        <div class="img-item-con">
            <?php the_post_thumbnail(); ?>
        </div>
    </div>
    <div class="content-item">
        <div class="date">
            <div class="date-img">
                <img src="<?php echo get_site_url() ?>/template/assets/images/calendar 1.svg" alt="">
            </div>
            <p class="date-text">
                <?php echo get_the_date(); ?>
            </p>
        </div>
        <a class="item-link" href="<?php the_permalink(); ?>">
            <?php the_title(); ?>
        </a>
        <a href="<?php the_permalink(); ?>" class="item-see">
            <?php echo __('Xem thêm', 'monamedia'); ?>
            <span class="icon">
                <i class="fas fa-chevron-right"></i>
            </span>
        </a>
    </div>
</div>