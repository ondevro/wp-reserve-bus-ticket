<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_PageTemplates {
	public static function get_routes($selected_route) {
		return query_posts(array(
						'post_type' => 'routes',
						'posts_per_page' => -1,
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key' => 'route_departure_location',
								'value' => sanitize_text_field($selected_route['route_departure_location'])
								),
							array(
								'key' => 'route_arrival_location',
								'value' => sanitize_text_field($selected_route['route_arrival_location'])
								)
						) ) );
	}

	public static function get_route($route_id) {
		return query_posts(array(
						'post_type' => 'routes',
						'posts_per_page' => 1,
						'p' => (int)$route_id
						) );
	}

	public static function get_route_locations($type) {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare("SELECT DISTINCT(meta_value) FROM $wpdb->postmeta WHERE meta_key = %s", $type ), ARRAY_A );
	}

	public static function departure_to_arrival_locations($departure) {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare("SELECT DISTINCT(meta_value) FROM $wpdb->postmeta WHERE meta_key = 'route_arrival_location' AND post_id IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'route_departure_location' AND meta_value = %s)", $departure), ARRAY_A );
	}

	public static function get_ticket_price($departure, $arrival) {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'route_price' AND post_id = (SELECT post_id FROM $wpdb->postmeta WHERE (meta_key = 'route_departure_location' AND meta_value = %s) AND post_id = (SELECT post_id FROM $wpdb->postmeta WHERE (meta_key = 'route_arrival_location' AND meta_value = %s) LIMIT 1))", $departure, $arrival), ARRAY_N );
	}

	public static function check_reservation($data) {
		return new WP_Query(array(
						'post_type' => 'reservations',
						'post_status' => 'pending',
						'post_date' => $data[3],
						'posts_per_page' => 1,
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key' => 'customer-email',
								'value' => sanitize_text_field($data[0])
								),
							array(
								'key' => 'route_departure_location',
								'value' => sanitize_text_field($data[1])
								),
							array(
								'key' => 'route_arrival_location',
								'value' => sanitize_text_field($data[2])
								)
						) ) );
	}
}