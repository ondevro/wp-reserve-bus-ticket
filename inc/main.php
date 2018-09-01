<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ReserveBusTicket {
	public $version = '1.0';
	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		$this->define_constants();
		$this->init_hooks();
		$this->includes();
	}

	private function includes() {
		include_once( RBT_ABSPATH . 'inc/class/rbt-post-types.php' );
		include_once( RBT_ABSPATH . 'inc/class/rbt-query.php' );
		include_once( RBT_ABSPATH . 'inc/class/rbt-ajax.php' );
	 	include_once( RBT_ABSPATH . 'inc/class/rbt-rewrite-rules.php' );
		include_once( RBT_ABSPATH . 'inc/class/rbt-page-generator.php' );

		add_action( 'plugins_loaded', array( 'RBT_PageGenerator', 'get_instance' ) );

		if ( is_admin() ) {
			$this->admin_includes();
		} else {
			$this->public_includes();
		}
	}

	private function init_hooks() {
		include_once( RBT_ABSPATH . 'inc/class/rbt-install.php' );
		include_once( RBT_ABSPATH . 'inc/class/rbt-uninstall.php' );
		include_once( RBT_ABSPATH . 'inc/class/rbt-page-generator.php' );
		register_activation_hook( RBT_PLUGIN_FILE, array( 'RBT_Install', 'install' ) );
		register_deactivation_hook( RBT_PLUGIN_FILE, array( 'RBT_Uninstall', 'uninstall') );
	}

	private function define_constants() {
		$this->define( 'RBT_ABSPATH', dirname( RBT_PLUGIN_FILE ) . '/' );
		$this->define( 'RBT_PLUGIN_BASENAME', plugin_basename( RBT_PLUGIN_FILE ) );
		$this->define( 'RBT_VERSION', $this->version );
	}

	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	public function public_includes() {
		include_once( RBT_ABSPATH . 'inc/class/rbt-shortcodes.php' );
	 	include_once( RBT_ABSPATH . 'inc/class/rbt-public-scripts.php' );
	 	include_once( RBT_ABSPATH . 'inc/class/rbt-cookie.php' );
	}

	public function admin_includes() {
		include_once( RBT_ABSPATH . 'inc/class/admin/rbt-admin.php' );
	}
}