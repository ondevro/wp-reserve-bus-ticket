<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( RBT_ABSPATH . '/inc/class/rbt-ajax.php' );

$cities = get_terms( array(
    'taxonomy' => 'cities',
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => false,
) );

if(empty($cities) || is_wp_error($cities)) {
	echo 'No cities found..';
} else {
?>
<script type="text/javascript">
jQuery(function($){
	$('[data-toggle="datepicker"]').datepicker({
	format: 'dd-mm-yyyy',
	date: new Date('<?php echo date('Y/m/d'); ?>'),
	startDate: new Date('<?php echo date('Y/m/d'); ?>')
});
});
</script>
<form action="ticket/" method="get">
	<div id="route_departure_location">
		<select name="route_departure_location">
			<option>Plecare din</option>
			<?php
				foreach(RBT_PageTemplates::get_route_locations('route_departure_location', null) as $departure_location) {
					echo '<option value="' . $departure_location['meta_value'] . '">' . $departure_location['meta_value'] . '</option>';
				}
			?>
		</select>
	</div>
	<div id="route_arrival_location">
		<select name="route_arrival_location">
			<option>Sosire in</option>
		</select>
	</div>
	<select name="route_passengers">
		<?php

			$passengers = get_terms( array(
			    'taxonomy' => 'passengers',
	            'orderby' => 'slug',
	            'order' => 'ASC',
			    'hide_empty' => false,
			) );

			foreach($passengers as $passenger) {
				echo '<option value="' . $passenger->slug . '">' . $passenger->name . '</option>';
			}
		?>
	</select>
	<input data-toggle="datepicker" name="route_date" value="<?php echo date('d-m-Y'); ?>">
	<span id="show_price"></span>
	<input type="submit" value="SHOW ROUTES" />
</form>
<?php
}
?>