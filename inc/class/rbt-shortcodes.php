<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_Shortcodes {
	public static function init () {
		$shortcodes = array(
			'ticket_form' => __CLASS__ . '::ticket_form'
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( $shortcode, $function );
		}
	}

	public static function ticket_form () {
		include_once( RBT_ABSPATH . '/templates/pages/rbt-main-form.php');
	}
}

RBT_Shortcodes::init();