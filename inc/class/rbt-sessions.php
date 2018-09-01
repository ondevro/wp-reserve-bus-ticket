<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_Sessions {
    private function __construct() {
        if(!session_id()) {
            session_set_cookie_params(3600);
            session_start();
        }
    }

    public static function userdata($name) {
        if(isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return FALSE;
        }
    }

    public static function set_userdata($data = array()) {
        foreach($data as $key => $value) {
            $_SESSION[sanitize_text_field($key)] = sanitize_text_field($value);
        }
    }

    public static function unset_userdata($name) {
        if(isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }
}