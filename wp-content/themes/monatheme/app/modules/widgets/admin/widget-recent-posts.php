<?php

/**
 * Undocumented class
 * Create widget
 */
class M_Widget_Recent_Posts extends WP_Widget
{

    /**
     * Undocumented function
     */
    function __construct()
    {
        parent::__construct(
            'm_recent_posts',
            __('Mona - Bài viết gần đây', 'mona-admin'),
            [
                'description' => __('Hiển thị bài viết mới nhất', 'mona-admin'),
            ]
        );
    }

    /**
     * Undocumented function
     *
     * @param [type] $args
     * @param [type] $instance
     * @return void
     */
    public function widget($args, $instance)
    {
        /* Giao diện web */

        // khai báo biến
        $title = isset($instance['title']) ? $instance['title'] : '';
        $quantity = isset($instance['quantity']) ? $instance['quantity'] : '';
        $select = isset($instance['select']) ? $instance['select'] : '';

        // before widget
        echo $args['before_widget'];
        echo $args['before_title'] . $title . $args['after_title'];
        echo '<div class="content">';

        // body
        $p_args = array(
            'cat'       => ($select > 0) ? $select : '',
            'post_type' => 'post',
            'posts_per_page' => (is_numeric($quantity)) ? ceil($quantity) : 4,
            'ignore_sticky_posts' => 1,
        );
        $getposts = new WP_Query($p_args);
        if ($getposts->have_posts()) :
            while ($getposts->have_posts()) : $getposts->the_post();
                /**
                 * GET TEMPLATE
                 * BOX POST
                 */
                $slug = '/partials/loop/box-post-vertical';
                echo get_template_part($slug);
            endwhile;
            wp_reset_postdata();
        endif;

        // after widget
        echo '</div>';
        echo $args['after_widget'];
    }

    /**
     * Undocumented function
     *
     * Widget Backend
     * @param [type] $instance
     * @return void
     */
    public function form($instance)
    {
        /* Tiêu đề */
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = '';
        }

        if (class_exists('Mona_Widgets')) {
            Mona_Widgets::create_field(
                [
                    'type'        => 'text',
                    'name'        => $this->get_field_name('title'),
                    'id'          => $this->get_field_id('title'),
                    'value'       => $title,
                    'title'       => 'Tiêu đề',
                    'placeholder' => 'Nhập nội dung văn bản',
                    'docs'        => false,
                ]
            );
        }

        /* Số lượng bài viết */
        if (isset($instance['quantity'])) {
            $quantity = $instance['quantity'];
        } else {
            $quantity = '';
        }

        if (class_exists('Mona_Widgets')) {
            Mona_Widgets::create_field(
                [
                    'type'        => 'text',
                    'name'        => $this->get_field_name('quantity'),
                    'id'          => $this->get_field_id('quantity'),
                    'value'       => $quantity,
                    'title'       => 'Số lượng bài viết hiển thị',
                    'placeholder' => 'Nhập số lượng',
                    'docs'        => false,
                ]
            );
        }

        /* Chọn chuyên mục */
        // Lấy danh sách chuyên mục
        $args = array(
            'type'          => 'post',
            'taxonomy'      => 'category',
            'orderby'       => 'id',
            'hide_empty'    => 0,
        );
        $categories = get_categories($args);

        // thêm vào options
        $options = array();
        foreach ($categories as $item) {
            $options[$item->term_id] = $item->name;
        }

        if (isset($instance['select'])) {
            $select = $instance['select'];
        } else {
            $select = '';
        }

        if (class_exists('Mona_Widgets')) {
            Mona_Widgets::create_field(
                [
                    'type'         => 'select',
                    'name'         => $this->get_field_name('select'),
                    'id'           => $this->get_field_id('select'),
                    'value'        => $select,
                    'title'        => 'Chọn chuyên mục',
                    'placeholder'  => 'Mặc định',
                    'select'       => $options,
                    'docs'         => false,
                ]
            );
        }
    }

    /**
     * Undocumented function
     *
     * Updating widget replacing old instances with new
     * @param [type] $new_instance
     * @param [type] $old_instance
     * @return void
     */
    public function update($new_instance, $old_instance)
    {
        $instance = [];
        if (class_exists('Mona_Widgets')) {
            $instance['title'] = Mona_Widgets::update_field($new_instance['title']);
            $instance['quantity'] = Mona_Widgets::update_field($new_instance['quantity']);
            $instance['select'] = Mona_Widgets::update_field($new_instance['select']);
        }
        return $instance;
    }
}

/**
 * Undocumented function
 *
 * Register and load the widget
 * @return void
 */
function Register_Widget_Recent_Posts()
{
    register_widget('M_Widget_Recent_Posts');
}
add_action('widgets_init', 'Register_Widget_Recent_Posts');
