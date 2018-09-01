<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class RBT_Uninstall {
	public static function uninstall() {
		flush_rewrite_rules();
	}
}