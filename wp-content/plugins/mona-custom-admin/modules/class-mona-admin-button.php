<?php
/**
 * Admin init
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Mona_Admin_Button' ) ) :

	class Mona_Admin_Button extends Mona_Admin_init {

		public $active_name = 'mona_button_active';

		public function __construct() {

			add_action( 'admin_init', [$this, 'mona_regsiter_active'] );

		}

		public function mona_regsiter_active() {

			register_setting( $this->button_setting_name, $this->active_name );

		}

		public static function mona_get_setting( $setting_name = '', $args = [] ) {

			$seeting_value = get_option( $setting_name );

			if ( isset ( $seeting_value ) ) {

				return $seeting_value;

			} else {

				return false;

			}

		}

	}

	new Mona_Admin_Button();

endif;
