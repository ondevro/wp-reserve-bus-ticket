<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_Cookie {
	public static function set_cookie($data = array()) {
        foreach($data as $key => $value) {
            setcookie($key, $value, time() + 86400, COOKIEPATH, COOKIE_DOMAIN);
        }
	}

	public static function get_cookie($name) {
		return $_COOKIE[$name];
	}

	public static function unset_cookie($name) {
		unset_cookie($name);
	}
}