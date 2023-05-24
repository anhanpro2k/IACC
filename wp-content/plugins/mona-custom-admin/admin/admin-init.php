<?php
/**
 * Admin init
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Mona_Admin_init' ) ) :

	class Mona_Admin_init {

		/**
		 * Undocumented variable
		 *
		 * @var [type]
		 */
		private $menu_parent;

		private $sub_menu_woo;

		private $sub_menu_button;

		public $button_setting_name = 'mona-admin-button';

		public function __construct() {

			$this->menu_parent     = 'mona-admin-general';
			$this->sub_menu_woo    = 'mona-admin-woocommerce';
			$this->sub_menu_button = 'mona-admin-button';

			if ( defined( 'MONA_SETTING' ) && MONA_SETTING == true ) {

				add_action( 'admin_menu', [$this, 'mona_add_parent_menu'] );
				add_action( 'admin_menu', [$this, 'mona_add_sub_menu'] );

			}

		}

		/**
		 * Undocumented function
		 *
		 * @return void
		 */
		public function mona_add_parent_menu() {

			add_menu_page(
				__( 'Mona Custom Admin', 'mona-admin' ),
				__( 'Mona Admin', 'mona-admin' ),
				'manage_options',
				$this->menu_parent,
				[$this, 'mona_template_general'],
				'dashicons-filter',
				99
			);

		}

		/**
		 * Undocumented function
		 *
		 * @return void
		 */
		public function mona_add_sub_menu() {

			add_submenu_page(
				$this->menu_parent,
				__( 'Tổng quan', 'mona-admin' ),
				__( 'Tổng quan', 'mona-admin' ),
				'manage_options',
				$this->menu_parent,
				[$this, 'mona_template_general']
			);

			if ( defined( 'MONA_SETTING_WOO' ) && MONA_SETTING_WOO == true ) {

				add_submenu_page(
					$this->menu_parent,
					__( 'Wocommerce', 'mona-admin' ),
					__( 'Wocommerce', 'mona-admin' ),
					'manage_options',
					$this->sub_menu_woo,
					[$this, 'mona_template_woocommerce']
				);

			} else {
				remove_menu_page( $this->sub_menu_woo );
			}

			if ( defined( 'MONA_SETTING_WOO' ) && MONA_SETTING_BUTTON == true ) {

				add_submenu_page(
					$this->menu_parent,
					__( 'Nút thông tin liên hệ - Fixed, Sticky, Animation Button', 'mona-admin' ),
					__( 'Nút liên hệ', 'mona-admin' ),
					'manage_options',
					$this->sub_menu_button,
					[$this, 'mona_template_button_contact']
				);

			} else {
				remove_menu_page( $this->sub_menu_button );
			}

		}

		/**
		 * Undocumented function
		 *
		 * @return void
		 */
		public function mona_template_general() {
			?>
			<div class="mgeneral-box-layout">
				<div class="mgeneral-box">
					<form id="mona-frm-submit-general" method="POST" action="options.php" autocomplete="off">
						
					</form>
				</div>
			</div>
			<?php
		}

		/**
		 * Undocumented function
		 *
		 * @return void
		 */
		public function mona_template_woocommerce() {

			esc_html_e( 'Update Woo....', 'textdomain' );

		}

		/**
		 * Undocumented function
		 *
		 * @return void
		 */
		public function mona_template_button_contact() {
			?>
			<div class="mbutton-box-layout">
				<div class="mbutton-box">
					<form id="mona-frm-submit-button-contact" method="POST" action="options.php" autocomplete="off">
						<?php
						settings_fields( $this->button_setting_name );
						?>
						<?php
						include( MONA_ADMIN_PLUGIN_ADMIN_DIR . '/button-template/header-template.php' );

						include( MONA_ADMIN_PLUGIN_ADMIN_DIR . '/button-template/main-template.php' );

						include( MONA_ADMIN_PLUGIN_ADMIN_DIR . '/button-template/footer-template.php' );
						?>
					</form>
				</div>
			</div>
			<?php
		}

	}

endif;
