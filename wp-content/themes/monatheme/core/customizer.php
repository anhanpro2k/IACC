<?php

if (class_exists('Kirki')) {

    /**
     * Add sections
     */
    function kirki_demo_scripts()
    {
        wp_enqueue_style('kirki-demo', get_stylesheet_uri(), array(), time());
    }

    add_action('wp_enqueue_scripts', 'kirki_demo_scripts');

    $priority = 1;

    /**
     * Add panel
     */
    // Kirki::add_panel( 'panel_contacts', 
    //     [
    //         'title'     => __( 'Liên hệ', 'mona-admin' ),
    //         'priority'   => $priority++,
    //         'capability' => 'edit_theme_options',
    //     ]
    // );

    /**
     * Add section
     */
    Kirki::add_section(
        'section_poster',
        [
            'title'      => __('Poster quảng cáo', 'mona-admin'),
            'priority'   => $priority++,
            'capability' => 'edit_theme_options',
        ]
    );

    /**
     * Add field
     */
    Kirki::add_field(
        'mona_setting',
        [
            'type'        => 'image',
            'settings'    => 'section_poster_img',
            'label'       => __('Ảnh nền', 'mona-admin'),
            'description' => '',
            'help'        => '',
            'section'     => 'section_poster',
            'default'     => '',
            'priority'    => $priority++,
        ]
    );

    /**
     * Add field
     */
    Kirki::add_field(
        'mona_setting',
        [
            'type'        => 'editor',
            'settings'    => 'section_poster_desc',
            'label'       => __('Mô tả', 'mona-admin'),
            'description' => '',
            'help'        => '',
            'section'     => 'section_poster',
            'default'     => '',
            'priority'    => $priority++,
        ]
    );

    /**
     * Add field
     */
    Kirki::add_field(
        'mona_setting',
        [
            'type'        => 'text',
            'settings'    => 'section_poster_btntext',
            'label'       => __('Chữ hiển thị nút', 'mona-admin'),
            'description' => '',
            'help'        => '',
            'section'     => 'section_poster',
            'default'     => '',
            'priority'    => $priority++,
        ]
    );

    /**
     * Add field
     */
    Kirki::add_field(
        'mona_setting',
        [
            'type'        => 'link',
            'settings'    => 'section_poster_btnurl',
            'label'       => __('Link nút', 'mona-admin'),
            'description' => '',
            'help'        => '',
            'section'     => 'section_poster',
            'default'     => '',
            'priority'    => $priority++,
        ]
    );

    /**
     * Add panel
     */
    Kirki::add_panel(
        'panel_footer',
        [
            'title'      => __('Thông tin Footer', 'mona-admin'),
            'priority'   => $priority++,
            'capability' => 'edit_theme_options',
        ]
    );

    /**
     * Add section
     */
    Kirki::add_section( 'section_footer_socials',
        [
            'title'      => __('Mạng xã hội', 'mona-admin'),
            'priority'   => $priority++,
            'capability' => 'edit_theme_options',
            'panel'      => 'panel_footer',
        ]
    );

    /**
     * Add field
     */
    Kirki::add_field( 'mona_setting',
        [
            'type'        => 'text',
            'settings'    => 'footer_title',
            'label'       => __('Tiêu đề', 'mona-admin'),
            'description' => '',
            'help'        => '',
            'section'     => 'section_footer_socials',
            'default'     => '',
            'priority'    => $priority++,
        ]
    );

    /**
     * Add field 
     */
    kirki::add_field('mona_setting', [
        'type'        => 'repeater',
        'label'       => __('Danh sách liên kết', 'mona-admin'),
        'section'     => 'section_footer_socials',
        'priority'    =>  $priority++,
        'row_label' => [
            'type'  => 'text',
            'value' => __('Liên kết', 'mona-admin'),
        ],
        'button_label' => __('Thêm mới', 'mona-admin'),
        'settings'     => 'footer_socials',
        'fields' => [
            'icon' => [
                'type'        => 'image',
                'label'       => __('Icon', 'mona-admin'),
                'description' => '',
                'default'     => '',
            ],
            'link' => [
                'type'        => 'text',
                'label'       => __('Link', 'mona-admin'),
                'description' => '',
                'default'     => '',
            ],
        ]
    ]);

    /**
     * Add section
     */
    Kirki::add_section( 'section_footer_bct',
        [
            'title'      => __('Bộ công thương', 'mona-admin'),
            'priority'   => $priority++,
            'capability' => 'edit_theme_options',
            'panel'      => 'panel_footer',
        ]
    );

    /**
     * Add field
     */
    Kirki::add_field('mona_setting', [
        'type'        => 'repeater',
        'label'       => __('Danh sách liên kết', 'mona-admin'),
        'section'     => 'section_footer_bct',
        'priority'    =>  $priority++,
        'row_label' => [
            'type'  => 'text',
            'value' => __('Liên kết', 'mona-admin'),
        ],
        'button_label' => __('Thêm mới', 'mona-admin'),
        'settings'     => 'footer_bct',
        'fields' => [
            'icon' => [
                'type'        => 'image',
                'label'       => __('Hình ảnh', 'mona-admin'),
                'description' => '',
                'default'     => '',
            ],
            'link' => [
                'type'        => 'text',
                'label'       => __('Link', 'mona-admin'),
                'description' => '',
                'default'     => '',
            ],
        ]
    ]);
}

if (!function_exists('mona_option')) {

    /**
     * Undocumented function
     *
     * @param [type] $setting
     * @param string $default
     * @return void
     */
    function mona_option($setting, $default = '')
    {
        echo mona_get_option($setting, $default);
    }

    /**
     * Undocumented function
     *
     * @param [type] $setting
     * @param string $default
     * @return void
     */
    function mona_get_option($setting, $default = '')
    {

        if (class_exists('Kirki')) {

            $value = $default;

            $options = get_option('option_name', array());
            $options = get_theme_mod($setting, $default);

            if (isset($options)) {
                $value = $options;
            }

            return $value;
        }

        return $default;
    }
}
