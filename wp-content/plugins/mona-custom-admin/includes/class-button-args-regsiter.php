<?php
/**
 * Admin init
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Regsiter_Button' ) ) :

	class Regsiter_Button extends Mona_Admin_Button {

		public function __construct() {

            parent::__construct();

		}

	}

	new Regsiter_Button();

endif;
