<?php
/**
 * Plugin Name: Mona Custom Admin
 * Plugin URI:
 * Description: Tùy biến lại trang quản trị của admin.
 * Version: 2.0
 * Author: Mona Media
 * Author URI: https://mona.media/
 * License: GPL2 etc
 * License URI: https://mona.media/ to your plugin license
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct\'s not allowed' );
}

define( 'MONA_ADMIN_TEXT_DOMAIN', 'mona-custom-admin' );

define( 'MONA_ADMIN_PLUGIN', __FILE__ );

define( 'MONA_ADMIN_PLUGIN_PATH', plugin_dir_path( MONA_ADMIN_PLUGIN ) );

define( 'MONA_ADMIN_PLUGIN_URL', plugins_url( '', MONA_ADMIN_PLUGIN ) );

define( 'MONA_ADMIN_PLUGIN_BASENAME', plugin_basename( MONA_ADMIN_PLUGIN ) );

define( 'MONA_ADMIN_PLUGIN_NAME', trim( dirname( MONA_ADMIN_PLUGIN_BASENAME ), '/' ) );

define( 'MONA_ADMIN_PLUGIN_DIR', dirname( MONA_ADMIN_PLUGIN ) );

define( 'MONA_ADMIN_PLUGIN_ADMIN_DIR', MONA_ADMIN_PLUGIN_DIR . '/admin' );

define( 'MONA_ADMIN_PLUGIN_MODULES_DIR', MONA_ADMIN_PLUGIN_DIR . '/modules' );

define( 'MONA_ADMIN_PLUGIN_FUNCTIONS_DIR', MONA_ADMIN_PLUGIN_DIR . '/functions' );

define( 'MONA_ADMIN_PLUGIN_INCLUDES_DIR', MONA_ADMIN_PLUGIN_DIR . '/includes' );

define( 'MONA_SETTING', true );


/**
 * Includes - setting admin
 */
include( MONA_ADMIN_PLUGIN_ADMIN_DIR . '/admin-init.php' );

/**
 * Includes - modules/button
 */
include( MONA_ADMIN_PLUGIN_MODULES_DIR . '/class-mona-admin-button.php' );
include( MONA_ADMIN_PLUGIN_INCLUDES_DIR . '/class-button-args-regsiter.php' );

if ( ! class_exists( 'Mona_Custom_Admin' ) ) :

    class Mona_Custom_Admin {

        /**
         * Undocumented variable
         *
         * @var string
         */
        public $version = '2.0';

        /**
         * Undocumented function
         */
        public function __construct() {

            add_action( 'login_enqueue_scripts', [$this, 'mona_style_login_template'] );

            add_action( 'admin_enqueue_scripts', [$this, 'mona_style_admin_template'] );

            $this->admin_init();

        }

        /**
         * Undocumented function
         *
         * @return void
         */
        public function mona_style_login_template() {

            wp_enqueue_media();
            // loading css
            wp_enqueue_style( 'mona-style-login-template', MONA_ADMIN_PLUGIN_URL . '/assets/css/login-template.css', array(), $this->version, 'all' );

            wp_enqueue_style( 'mona-style-button-template', MONA_ADMIN_PLUGIN_URL . '/assets/css/button-template.css', array(), $this->version, 'all' );

        }

        /**
         * Undocumented function
         *
         * @return void
         */
        public function mona_style_admin_template() {

            wp_enqueue_media();
            // loading css
            wp_enqueue_style( 'mona-style-button-template', MONA_ADMIN_PLUGIN_URL . '/assets/css/button-template.css', array(), $this->version, 'all' );

        }

        /**
         * Undocumented function
         *
         * @return void
         */
        public function admin_init() {

            new Mona_Admin_init();

        }

    }

    new Mona_Custom_Admin();

endif;
