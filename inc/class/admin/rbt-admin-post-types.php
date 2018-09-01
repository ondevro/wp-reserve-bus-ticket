<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_AdminPostTypes {
	public static function init () {
		add_action('admin_menu', array( __CLASS__, 'menu_rbt' ) );

    	add_action( 'admin_init', array( __CLASS__, 'setup_sections' ) );
    	add_action( 'admin_init', array( __CLASS__, 'setup_fields' ) );

	    add_filter( 'manage_edit-cities_columns', array( __CLASS__, 'remove_taxonomy_column_description' ) );
	    add_filter( 'manage_edit-passengers_columns', array( __CLASS__, 'remove_taxonomy_column_description' ) );
	    add_action( 'cities_add_form', array( __CLASS__, 'remove_taxonomy_form_description' ) );
	    add_action( 'passengers_add_form', array( __CLASS__, 'remove_taxonomy_form_description' ) );
	    add_action( 'cities_edit_form', array( __CLASS__, 'remove_taxonomy_form_description' ) );
	    add_action( 'passengers_edit_form', array( __CLASS__, 'remove_taxonomy_form_description' ) );

	    add_filter( 'manage_routes_posts_columns', array( __CLASS__, 'routes_custom_columns' ) );
	    add_action( 'manage_routes_posts_custom_column', array( __CLASS__, 'routes_custom_columns_data' ), 10, 2 );

	    add_filter( 'manage_reservations_posts_columns', array( __CLASS__, 'reservations_custom_columns' ) );
	    add_action( 'manage_reservations_posts_custom_column', array( __CLASS__, 'reservations_custom_columns_data' ), 10, 2 );
	}

	public static function menu_rbt() {
		add_menu_page( __( 'Reserve bus ticket', 'rbt' ), __( 'Reserve bus ticket', 'rbt' ), 'manage_options', RBT_PLUGIN_FILE, array( __CLASS__, 'reserve_bus_ticket' ) );

		add_submenu_page(RBT_PLUGIN_FILE, 'Add Route', 'Add Route', 'manage_options', 'post-new.php?post_type=routes');
		add_submenu_page(RBT_PLUGIN_FILE, 'Routes', 'Routes', 'manage_options', 'edit.php?post_type=routes');
		add_submenu_page(RBT_PLUGIN_FILE, 'Reservations', 'Reservations', 'manage_options', 'edit.php?post_type=reservations');
		add_submenu_page(RBT_PLUGIN_FILE, 'Cities', 'Cities', 'manage_options', 'edit-tags.php?taxonomy=cities&post_type=routes');
		add_submenu_page(RBT_PLUGIN_FILE, 'Passengers', 'Passengers', 'manage_options', 'edit-tags.php?taxonomy=passengers&post_type=routes');
	}

	public static function reserve_bus_ticket() {
		include_once(RBT_ABSPATH . 'templates/pages/rbt-admin-settings.php');
	}

    public static function setup_sections() {
        add_settings_section( 'google-recaptcha', 'Google recaptcha', array( __CLASS__, 'section_callback' ), 'smashing_fields' );
        add_settings_section( 'email-smtp', 'Email', array( __CLASS__, 'section_callback' ), 'smashing_fields' );
    }

    public static function section_callback( $arguments ) {
    	switch( $arguments['id'] ){
    		case 'google-recaptcha':
    			echo 'Insert data after you create api with V2 type for this website on your google account';
    			break;
    		case 'email-smtp':
    			echo 'Complete fields for SMTP configurations to send email confirmation';
    			break;
    	}
    }

    public static function setup_fields() {
		$fields = array(
		    array(
		        'uid' => 'rbt-recaptcha-public-key',
		        'label' => 'Recaptcha public key',
		        'section' => 'google-recaptcha',
		        'type' => 'text',
		        'size' => '50',
		        'placeholder' => 'Recaptcha public key from your google account',
		        'options' => false
		    ),
		    array(
		        'uid' => 'rbt-recaptcha-private-key',
		        'label' => 'Recaptcha private key',
		        'section' => 'google-recaptcha',
		        'type' => 'text',
		        'size' => '50',
		        'placeholder' => 'Recaptcha private key from your google account',
		        'options' => false
		    ),
		    array(
		        'uid' => 'rbt-email-smtp-receive-on',
		        'label' => 'Receive email address',
		        'section' => 'email-smtp',
		        'type' => 'text',
		        'size' => '30',
		        'placeholder' => 'your-email@address.com',
		        'underneath' => 'Email address to sent a copy about reservation (optional)',
		        'options' => false
		    ),
		    array(
		        'uid' => 'rbt-email-smtp-sent-from',
		        'label' => 'Sent email address',
		        'section' => 'email-smtp',
		        'type' => 'text',
		        'size' => '30',
		        'placeholder' => 'your-email@address.com',
		        'underneath' => 'Email address to sent from',
		        'options' => false
		    ),
		    array(
		        'uid' => 'rbt-email-subject',
		        'label' => 'Email subject',
		        'section' => 'email-subject',
		        'type' => 'text',
		        'size' => '30',
		        'placeholder' => 'Reservation confirm',
		        'underneath' => 'Email title',
		        'options' => false
		    ),
		    array(
		        'uid' => 'rbt-email-smtp-sent-from-name',
		        'label' => 'Sent from name',
		        'section' => 'email-smtp',
		        'type' => 'text',
		        'size' => '30',
		        'placeholder' => 'From name',
		        'underneath' => 'Specify email name from who is sent',
		        'options' => false
		    ),
		    array(
		        'uid' => 'rbt-email-smtp-host',
		        'label' => 'Host',
		        'section' => 'email-smtp',
		        'type' => 'text',
		        'size' => '30',
		        'placeholder' => 'Host address',
		        'underneath' => 'Server address to which sends email',
		        'options' => false
		    ),
		    array(
		        'uid' => 'rbt-email-username',
		        'label' => 'Username',
		        'section' => 'email-smtp',
		        'type' => 'text',
		        'size' => '30',
		        'placeholder' => 'Email address',
		        'underneath' => 'Insert email address for authentication',
		        'options' => false
		    ),
		    array(
		        'uid' => 'rbt-email-password',
		        'label' => 'Password',
		        'section' => 'email-smtp',
		        'type' => 'text',
		        'size' => '30',
		        'placeholder' => 'Email password',
		        'underneath' => 'Specify email password for authentication',
		        'options' => false
		    ),
		    array(
		        'uid' => 'rbt-email-smtp-port',
		        'label' => 'SMTP Port',
		        'section' => 'email-smtp',
		        'type' => 'text',
		        'size' => '10',
		        'placeholder' => '465',
		        'underneath' => 'SMTP port',
		        'options' => false
		    )
		);

    	foreach ( $fields as $field ) {
        	add_settings_field( $field['uid'], $field['label'], array( __CLASS__, 'field_callback' ), 'smashing_fields', $field['section'], $field );
            register_setting( 'smashing_fields', $field['uid'] );
    	}
    }

    public static function field_callback( $arguments ) {
        $value = get_option( $arguments['uid'] );
        /*if( ! $value ) {
            $value = $arguments['default'];
        }*/

		switch ( $arguments['type'] ) {
		    case 'text':
		        printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" size="%5$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value, $arguments['size'] );
		        break;
		    case 'textarea':
		        printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
		        break;
		    case 'select':
		        if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
		            $options_markup = '';
		            foreach( $arguments['options'] as $key => $label ){
		                $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
		            }
		            printf( '<select name="%1$s" id="%1$s">%2$s</select>', $arguments['uid'], $options_markup );
		        }
		        break;
		}

        if( isset($arguments['underneath']) ) {
            printf( '<p class="description">%s</p>', $arguments['underneath'] );
        }
    }

	public static function routes_custom_columns( $columns ) {
	    $columns = array(
	        'route_departure_location' => 'Departure location',
	        'route_arrival_location' => 'Arrival location',
	        'route_departure_time' => 'Departure time',
	        'route_arrival_time' => 'Arrival time',
	        'route_price' => 'Price'
	    );

	    return $columns;
	}

	public static function routes_custom_columns_data( $column, $post_id ) {
		$route_data = array(
							'route_departure_location' => get_post_meta($post_id, 'route_departure_location', true),
							'route_arrival_location' => get_post_meta($post_id, 'route_arrival_location', true),
							'route_departure_time' => get_post_meta($post_id, 'route_departure_time', true),
							'route_arrival_time' => get_post_meta($post_id, 'route_arrival_time', true),
							'route_price' => get_post_meta($post_id, 'route_price', true)
							);

	    switch ($column) {
		    case 'route_departure_location':
		        echo $route_data['route_departure_location'];
		        break;
		    case 'route_arrival_location':
		        echo $route_data['route_arrival_location'];
		        break;
		    case 'route_departure_time':
		        echo $route_data['route_departure_time'];
		        break;
		    case 'route_arrival_time':
		        echo $route_data['route_arrival_time'];
		        break;
		    case 'route_price':
		        echo $route_data['route_price'] . ' LEI';
		        break;
		    default:
		    	'';
		   	    break;
	    }
	}

	public static function reservations_custom_columns( $columns ) {
	    $columns = array(
	        'reservation_client' => 'Client',
	        'reservation_route' => 'Route',
	        'reservation_date' => 'Date',
	        'reservation_time' => 'Time',
	        'reservation_price' => 'Price'
	    );

	    return $columns;
	}

	public static function reservations_custom_columns_data( $column, $post_id ) {
		$route_data = array(
							'route_departure_location' => get_post_meta($post_id, 'route_departure_location', true),
							'route_arrival_location' => get_post_meta($post_id, 'route_arrival_location', true),
							'route_date' => get_post_meta($post_id, 'route_date', true),
							'route_departure_time' => get_post_meta($post_id, 'route_departure_time', true),
							'route_arrival_time' => get_post_meta($post_id, 'route_arrival_time', true),
							'route_price' => get_post_meta($post_id, 'route_price', true),
							'customer_name' => get_post_meta($post_id, 'customer-name', true),
							'customer_surname' => get_post_meta($post_id, 'customer-surname', true)
							);

	    switch ($column) {
		    case 'reservation_customer':
		        echo $route_data['customer_name'] . ' ' . $route_data['customer_surname'];
		        break;
		    case 'reservation_route':
		        echo $route_data['route_departure_location'] . ' ->' . $route_data['route_arrival_location'];
		        break;
		    case 'reservation_date':
		        echo $route_data['route_date'];
		        break;
		    case 'reservation_time':
		        echo $route_data['route_departure_time'] . ' ->' . $route_data['route_arrival_time'];
		        break;
		    case 'reservation_price':
		        echo $route_data['route_price'] . ' LEI';
		        break;
		    default:
		    	'';
		   	    break;
	    }
	}

	public function remove_taxonomy_column_description( $columns ) {
	    if( isset( $columns['description'] ) )
	        unset( $columns['description'] );

	    return $columns;
	}

	public function remove_taxonomy_form_description( $taxonomy ) {
		echo '<style>.term-description-wrap{display:none;}</style>';
	}
}

 RBT_AdminPostTypes::init();