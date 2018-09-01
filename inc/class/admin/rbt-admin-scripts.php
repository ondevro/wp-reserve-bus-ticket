<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_AdminScripts {
	public static function init() {
		add_action( 'admin_enqueue_scripts', array(__CLASS__, 'scripts' ) );
	}

	public static function scripts() {
	   	wp_register_script('jquery-clockpicker', plugins_url('assets/js/jquery-clockpicker.min.js', RBT_PLUGIN_FILE), array('jquery'), '0.0.7', true);
	   	wp_enqueue_script('jquery-clockpicker');

    	wp_register_style('css-clockpicker', plugins_url('assets/css/jquery-clockpicker.min.css', RBT_PLUGIN_FILE), null, '0.0.7');
	   	wp_enqueue_style('css-clockpicker');
    }
}

RBT_AdminScripts::init();