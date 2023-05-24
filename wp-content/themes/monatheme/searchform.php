<div class="km-right-search">
    <form method="get" id="searchform" class="searchform" action="<?php echo get_permalink(MONA_PAGE_BLOG); ?>">
        <input type="search" class="input-search" name="s" value="<?php echo get_search_query(); ?>" id="s" placeholder="<?php echo esc_attr_x('Tìm kiếm mã khuyến mãi', 'placeholder', 'monamedia'); ?>" />
        <button type="submit" class="btn-search">
            <span><i class="fas fa-search"></i></span>
        </button>
    </form>
</div>