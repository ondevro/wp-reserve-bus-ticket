<?php
/**
* Plugin Name: Reserve bus ticket
* Plugin URI: https://github.com/ondevro/reservation-bus-ticket
* Description: RBT provides tickets and clients management data also have features like showing data dynamically and confirm the reservation by email.
* Version: 1.0
* Author: Iulian
* Author URI: http://ondev.ro/
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ReserveBusTicket' ) ) {
	include_once dirname( __FILE__ ) . '/inc/main.php';
}

if ( ! defined( 'RBT_PLUGIN_FILE' ) ) {
	define( 'RBT_PLUGIN_FILE', __FILE__ );
}

ReserveBusTicket::instance();