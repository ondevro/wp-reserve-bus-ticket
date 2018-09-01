<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_Admin_Meta_Boxes {
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_events_metaboxes' ) );
		add_action( 'save_post', array( __CLASS__, 'save_routes_meta') ); 
	}

	public static function add_events_metaboxes() {
		add_meta_box(
			'route_form',
			'Route information',
			array( __CLASS__, 'route_form'),
			'routes',
			'normal',
			'high'
		);
	}

	public static function route_form() {
		global $post;

		wp_nonce_field( basename( __FILE__ ), 'route_fields' );

		$cities = get_terms(
					array(
						    'taxonomy' => 'cities',
						    'order_by' => 'name',
						    'hide_empty' => false
						)
				);

		echo '<select name="route_departure_location">';
		foreach ( $cities as $city ) {
	        echo '<option value="' . $city->name  .'">' . $city->name . '</option>';
	    }
	    echo '</select>';

		echo '<select name="route_arrival_location">';
		foreach ( $cities as $city ) {
	        echo '<option value="' . $city->name  .'">' . $city->name . '</option>';
	    }
	    echo '</select>';

		echo '<input type="text" name="route_departure_time" placeholder="departure time" class="clockpicker" />';
		echo '<input type="text" name="route_arrival_time" placeholder="arrival time" class="clockpicker" />';
		echo '<input type="text" name="route_price" placeholder="price for one ticket" />';


		echo "<script type=\"text/javascript\">
jQuery(function($){
	$('.clockpicker').clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: true
});
});
</script>";
	}

	public static function save_routes_meta($post_id) {
	    $post_type = get_post_type($post_id);

		if(!current_user_can('edit_post', $post_id) || $post_type != 'routes') {
			return;
		}

		$route_data = array(
							'route_departure_location' => $_POST['route_departure_location'],
							'route_arrival_location' => $_POST['route_arrival_location'],
							'route_departure_time' => $_POST['route_departure_time'],
							'route_arrival_time' => $_POST['route_arrival_time'],
							'route_price' => $_POST['route_price']
							);

		// Verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times.
		if(!isset($route_data['route_departure_location']) || !isset($route_data['route_arrival_location']) || !isset($route_data['route_departure_time']) || !isset($route_data['route_arrival_time']) || !isset($route_data['route_price'])) { // || !wp_verify_nonce($_POST['route_fields'], basename(__FILE__))) {
			return;
		}

		foreach($route_data as $key => $value) {
			// Don't store custom data twice
			if(get_post_meta($post_id, $key, false)) {
				// If the custom field already has a value, update it.
				update_post_meta($post_id, $key, sanitize_text_field($value));
			} else {
				// If the custom field doesn't have a value, add it.
				add_post_meta($post_id, $key, sanitize_text_field($value));
			}
		}

	}
}

RBT_Admin_Meta_Boxes::init();
?>