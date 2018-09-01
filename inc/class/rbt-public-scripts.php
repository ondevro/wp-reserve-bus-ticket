<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_PublicScripts {
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'scripts' ) );
	}

	public static function scripts() {
	   	wp_register_script('datepicker', plugins_url('assets/js/datepicker.min.js', RBT_PLUGIN_FILE), array('jquery'), '0.6.4', true);
	   	wp_enqueue_script('datepicker');

	   	wp_register_script("recaptcha", "https://www.google.com/recaptcha/api.js");
		wp_enqueue_script("recaptcha");

    	wp_register_style('datepicker', plugins_url('assets/css/datepicker.min.css', RBT_PLUGIN_FILE), null, '0.6.4');
	   	wp_enqueue_style('datepicker');
    }

}

RBT_PublicScripts::init();