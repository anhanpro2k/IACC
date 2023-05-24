<?php
/**
 * Undocumented class
 * Create widget
 */
class M_Widget_default extends WP_Widget {

    /**
     * Undocumented function
     */
    function __construct() 
    {
        parent::__construct(
            'm_default_text',
            __( 'Mona - Default Text', 'mona-admin' ),
            [
                'description' => __( 'Hiển thị nhập giá trị văn bản ngắn', 'mona-admin' ),
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
    public function widget( $args, $instance ) 
    {
        $widget_id = $args['widget_id'];
        $title = isset( $instance['title'] ) ? $instance['title'] : '';
        ?>
        <div class="mona-ft-text-default">
            <p><?php echo esc_attr( $title ) ?></p>
        </div>
        <?php
    }

    /**
     * Undocumented function
     *
     * Widget Backend
     * @param [type] $instance
     * @return void
     */
    public function form( $instance ) 
    {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = '';
        }

        if ( class_exists ( 'Mona_Widgets' ) ) {
            Mona_Widgets::create_field(
                [
                    'type'        => 'text',
                    'name'        => $this->get_field_name( 'title' ),
                    'id'          => $this->get_field_id( 'title' ),
                    'value'       => $title,
                    'title'       => __( 'Text', 'mona-admin' ),
                    'placeholder' => __( 'Nhập nội dung văn bản', 'mona-admin' ),
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
    public function update( $new_instance, $old_instance ) 
    {
        $instance = [];
        if ( class_exists ( 'Mona_Widgets' ) ) { 
            $instance['title'] = Mona_Widgets::update_field( $new_instance['title'] );
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
function Register_Widget_Default_Text() {
    register_widget( 'M_Widget_default' );
}
add_action( 'widgets_init', 'Register_Widget_Default_Text' );
