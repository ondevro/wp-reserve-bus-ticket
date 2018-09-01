<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class RBT_Admin {
	public static function init () {
		include_once( RBT_ABSPATH . 'inc/class/admin/rbt-admin-post-types.php' );
		include_once( RBT_ABSPATH . 'inc/class/admin/rbt-admin-meta-boxes.php' );
		include_once( RBT_ABSPATH . 'inc/class/admin/rbt-admin-scripts.php' );
	}
}

RBT_Admin::init();