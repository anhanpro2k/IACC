<?php

/**
 * Undocumented class
 * Create widget
 */
class M_List_icons extends WP_Widget
{

    /**
     * Undocumented function
     */
    function __construct()
    {
        parent::__construct(
            'm_list_icons',
            __('Mona - Danh sách Icon', 'mona-admin'),
            [
                'description' => __('Nhập danh sách thông tin có icon', 'mona-admin'),
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
        $widget_id = $args['widget_id'];
        $title = isset($instance['title']) ? $instance['title'] : '';
        $list_items = isset($instance['list_items']) ? $instance['list_items'] : '';

        echo $args['before_widget'];
        echo $args['before_title'] . $title . $args['after_title'];
        echo '<ul class="footer-list">';
        if (content_exists($list_items)) :
            foreach ($list_items as $item) :
                if ($item['item_text'] && $item['item_img']) :
                    ?>
                    <li class="footer-item">
                        <a href="<?php echo ($item['item_url']) ? esc_url($item['item_url']) : 'javascript:;'; ?>" class="footer-link">
                            <span class="footer-link-wrap">
                                <span class="icon">
                                    <img src="<?php echo $item['item_img']; ?>" alt="icon-footer">
                                </span>
                                <span class="text"><?php echo $item['item_text']; ?></span>
                            </span>
                        </a>
                    </li>
                    <?php
                endif;
            endforeach;
        endif;
        echo '</ul>';
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
                    'title'       => __('Tiêu đề', 'mona-admin'),
                    'placeholder' => __('Nhập nội dung văn bản', 'mona-admin'),
                    'docs'        => false,
                ]
            );
        }

        /* ---- */
        if (isset($instance['list_items'])) {
            $list_items = $instance['list_items'];
        } else {
            $list_items = '';
        }

        if (class_exists('Mona_Widgets')) {
            Mona_Widgets::create_field(
                [
                    'type'        => 'repeater',
                    'name'        => $this->get_field_name('list_items'),
                    'id'          => $this->get_field_id('list_items'),
                    'value'       => $list_items,
                    'title'       => __('Danh sách icon', 'mona-admin'),
                    'fields'      => [
                        'item_img' => [
                            'type'        => 'image',
                            'id'          => $this->get_field_id('item_img'),
                            'title'       => __('Hình ảnh Icon', 'monamedia'),
                        ],
                        'item_text' => [
                            'type'        => 'textarea',
                            'rows'        => '2',
                            'title'       => __('Chữ hiển thị', 'monamedia'),
                            'placeholder' => __('Nhập nội dung văn bản', 'monamedia'),
                        ],
                        'item_url' => [
                            'type'        => 'textarea',
                            'rows'        => '2',
                            'title'       => __('Liên kết', 'monamedia'),
                            'placeholder' => __('Nhập nội dung văn bản', 'monamedia'),
                        ],
                    ],
                    'docs'        => false,
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
            $instance['list_items'] = Mona_Widgets::update_field($new_instance['list_items']);
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
function Register_Widget_List_Icons()
{
    register_widget('M_List_icons');
}
add_action('widgets_init', 'Register_Widget_List_Icons');
