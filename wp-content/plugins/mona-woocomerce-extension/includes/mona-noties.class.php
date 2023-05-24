<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage the notices of the plugin
 *
 * @author   htdat
 * @since    1.3.1
 *
 */
class Mona_Notices {

	var $settings = '';

	static $default_settings = array(
		
	);
	public function __construct() {
		$this->settings = self::get_settings();

	}

	public function get_settings() {
		$settings = get_option( 'monamedia_notices', self::$default_settings );
		$settings = wp_parse_args( $settings, self::$default_settings );

		return $settings;
	}
	public function save_settings() {
		update_option( 'monamedia_notices', $this->settings );
	}


}