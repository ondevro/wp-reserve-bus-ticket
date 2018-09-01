<?php
/*
 * Template Name: Ticket
 * Description: Template showing routes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(isset($_POST['reserve_ticket']) && isset($_POST['route'])) {
	$route_id = (int) $_POST['route'];

	if($route_id) {
		RBT_Cookie::set_cookie(array(
										'route_departure_location' => get_post_meta($route_id, 'route_departure_location', true),
										'route_arrival_location'   => get_post_meta($route_id, 'route_arrival_location', true),
										'route_date'               => get_post_meta($route_id, 'route_date', true),
										'route_departure_time'     => get_post_meta($route_id, 'route_departure_time', true),
										'route_arrival_time'       => get_post_meta($route_id, 'route_arrival_time', true),
										'route_price'              => get_post_meta($route_id, 'route_price', true)
									));

		wp_redirect(site_url('/reserve-ticket/'));
	}
}

get_header();
	$user_route_data = array(
					'route_departure_location' => $_GET['route_departure_location'],
					'route_arrival_location' => $_GET['route_arrival_location'],
					'route_passengers' => (int) $_GET['route_passengers']
				);

	$routes = RBT_PageTemplates::get_routes($user_route_data);

	if($routes) {
		echo '<form action="" method="post">
		<table>
			<thead>
				<th>Departure location</th>
				<th>Arrival location</th>
				<th>Departure time</th>
				<th>Arrival time</th>
				<th>Price</th>
				<th></th>
			</thead>
			<tbody>
		';

		foreach($routes as $route) {
			$route_data = array(
									'route_departure_location' => get_post_meta($route->ID, 'route_departure_location', true),
									'route_arrival_location' => get_post_meta($route->ID, 'route_arrival_location', true),
									'route_departure_time' => get_post_meta($route->ID, 'route_departure_time', true),
									'route_arrival_time' => get_post_meta($route->ID, 'route_arrival_time', true),
									'route_price' => get_post_meta($route->ID, 'route_price', true)
								);
			echo '<tr>
					<td><label for="route' . $route->ID . '">' . $route_data['route_departure_location'] . '</label></td>
					<td><label for="route' . $route->ID . '">' . $route_data['route_arrival_location'] . '</label></td>
					<td><label for="route' . $route->ID . '">' . $route_data['route_departure_time'] . '</label></td>
					<td><label for="route' . $route->ID . '">' . $route_data['route_arrival_time'] . '</label></td>
					<td><label for="route' . $route->ID . '">' . $route_data['route_price'] * $user_route_data['route_passengers'] . '</label></td>
					<td><label for="route' . $route->ID . '"><input type="radio" name="route" value="' . $route->ID . '" id="route' . $route->ID . '"> Alegeti cursa</label></td>
				</tr>
			';
		}

		echo '</tbody></table>';
		echo '<input name="reserve_ticket" type="submit" value="PASUL URMATOR" /></form>';
	} else {
		echo 'Sorry, no routes found!';
	}

get_footer();