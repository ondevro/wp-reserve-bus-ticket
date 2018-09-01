<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_Ajax {
    public static function init() {
        add_action( 'wp_enqueue_scripts', array( __CLASS__ , 'load_script' ) );
        add_action( 'wp_ajax_process_route_departure_location', array( __CLASS__, 'process_route_departure_location' ) ); 
        add_action( 'wp_ajax_nopriv_process_route_departure_location', array( __CLASS__, 'process_route_departure_location' ) );
        add_action( 'wp_ajax_process_route_arrival_location', array( __CLASS__, 'process_route_arrival_location' ) ); 
        add_action( 'wp_ajax_nopriv_process_route_arrival_location', array( __CLASS__, 'process_route_arrival_location' ) );
    }

    public static function load_script() {
        wp_register_script( 'ajax_route_departure_location', plugins_url( 'assets/js/get-route-arrival-location.js', RBT_PLUGIN_FILE ), array('jquery') );
        wp_enqueue_script('ajax_route_departure_location');
		wp_localize_script( 'ajax_route_departure_location', 'myAjax', array( 'url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( "process_route_departure_location" ), 'route_departure_location' => '' ) );
		wp_localize_script( 'ajax_route_arrival_location', 'myAjax', array( 'url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( "process_route_arrival_location" ), 'route_departure_location' => RBT_Cookie::get_cookie('route_departure_location'), 'route_arrival_location' => RBT_Cookie::get_cookie('route_arrival_location') ) );
    }

    public static function process_route_departure_location() {
		$arr = array('success' => true, 'locations' => self::get_arrival_locations($_POST['deparute_location']));

		echo json_encode($arr);

        die();
    }

    public static function process_route_arrival_location() {
		$arr = array('success' => true, 'price' => self::ticket_price($_POST['route_departure_location'], $_POST['route_arrival_location']));

		echo json_encode($arr);

        die();
    }

	private static function get_arrival_locations($deparute_location) {
		return RBT_PageTemplates::departure_to_arrival_locations($deparute_location);
	}

	private static function ticket_price($deparute_location, $arrival_location) {
		return RBT_PageTemplates::get_ticket_price($deparute_location, $arrival_location);
	}
}

RBT_Ajax::init();