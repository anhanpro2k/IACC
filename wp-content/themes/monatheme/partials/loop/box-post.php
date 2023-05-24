<div class="item-content d-2 d-item">
    <a href="<?php the_permalink(); ?>" class="item-img">
        <?php the_post_thumbnail(); ?>
    </a>
    <div class="author">
        <div class="author-img">
            <img src="<?php echo get_site_url() ?>/template/assets/images/author.svg" alt="">
        </div>
        <p class="author-text">
            <?php the_author(); ?>
        </p>
    </div>
    <h3>
        <a href="<?php the_permalink(); ?>" class="item-link">
            <?php the_title(); ?>
        </a>
    </h3>
    <p class="text">
        <?php echo wp_trim_words(get_the_excerpt(), 30, "."); ?>
    </p>
    <a href="<?php the_permalink(); ?>" class="btn icon-right">
        <span class="btn-inner">
            <span class="text"><?php echo __('Xem chi tiết', 'monamedia'); ?></span>

            <span class="icon">
                <i class="fas fa-chevron-right"></i>
            </span>
        </span>
    </a>
</div>