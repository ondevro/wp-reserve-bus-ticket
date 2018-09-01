<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_PostTypes {
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
	}

	public static function register_post_types() {
		$labels = array(
			'name'               => _x( 'Routes', 'post type general name', 'your-plugin-textdomain' ),
			'singular_name'      => _x( 'Route', 'post type singular name', 'your-plugin-textdomain' ),
			'menu_name'          => _x( 'Routes', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar'     => _x( 'Route', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new'            => _x( 'New route', 'post', 'your-plugin-textdomain' ),
			'add_new_item'       => __( 'Add New Route', 'your-plugin-textdomain' ),
			'new_item'           => __( 'New Route', 'your-plugin-textdomain' ),
			'edit_item'          => __( 'Edit Route', 'your-plugin-textdomain' ),
			'view_item'          => __( 'View Route', 'your-plugin-textdomain' ),
			'all_items'          => __( 'All Routes', 'your-plugin-textdomain' ),
			'search_items'       => __( 'Search Routes', 'your-plugin-textdomain' ),
			'parent_item_colon'  => __( 'Parent Routes:', 'your-plugin-textdomain' ),
			'not_found'          => __( 'No routes found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No routes found in Trash.', 'your-plugin-textdomain' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'Description.', 'your-plugin-textdomain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=routes',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'routes' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => false
		);

		register_post_type( 'routes', $args );


		$labels = array(
			'name'               => _x( 'Reservations', 'post type general name', 'your-plugin-textdomain' ),
			'singular_name'      => _x( 'Reservation', 'post type singular name', 'your-plugin-textdomain' ),
			'menu_name'          => _x( 'Reservations', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar'     => _x( 'Reservation', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new'            => _x( 'New reservation', 'post', 'your-plugin-textdomain' ),
			'add_new_item'       => __( 'Add New Reservation', 'your-plugin-textdomain' ),
			'new_item'           => __( 'New Reservation', 'your-plugin-textdomain' ),
			'edit_item'          => __( 'Edit Reservation', 'your-plugin-textdomain' ),
			'view_item'          => __( 'View Reservation', 'your-plugin-textdomain' ),
			'all_items'          => __( 'All Reservations', 'your-plugin-textdomain' ),
			'search_items'       => __( 'Search Reservations', 'your-plugin-textdomain' ),
			'parent_item_colon'  => __( 'Parent Reservations:', 'your-plugin-textdomain' ),
			'not_found'          => __( 'No routes found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No routes found in Trash.', 'your-plugin-textdomain' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'Description.', 'your-plugin-textdomain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=reservations',
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'reservations' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => false
		);

		register_post_type( 'reservations', $args );
	}

	public static function register_taxonomies() {
 		register_taxonomy( 'cities', 'routes', array(
			'labels'            => array(
				'name'					=> __( 'Cities', 'my_theme' ),
				'singular_name'			=> __( 'Citie', 'my_theme' ),
				'search_items'			=> __( 'Search Cities', 'my_theme' ),
				'popular_items'			=> __( 'Popular Cities', 'my_theme' ),
				'all_items'				=> __( 'All Cities', 'my_theme' ),
				'parent_item'			=> __( 'Parent Citie', 'my_theme' ),
				'parent_item_colon'		=> __( 'Parent Citie', 'my_theme' ),
				'edit_item'				=> __( 'Edit Citie', 'my_theme' ),
				'update_item'			=> __( 'Update Citie', 'my_theme' ),
				'add_new_item'			=> __( 'Add New Citie', 'my_theme' ),
				'new_item_name'			=> __( 'New Citie Name', 'my_theme' ),
				'add_or_remove_items'	=> __( 'Add or remove Cities', 'my_theme' ),
				'choose_from_most_used'	=> __( 'Choose from most used', 'my_theme' ),
				'menu_name'				=> __( 'Cities', 'my_theme' ),
			),
			'show_admin_column'  => true,
		    'show_ui'            => 'edit-tags.php?taxonomy=cities&post_type=routes',
		    'show_in_quick_edit' => false,
		    'meta_box_cb'        => false
		) );

 		register_taxonomy( 'passengers', 'routes', array(
			'labels'            => array(
				'name'					=> __( 'Passengers', 'my_theme' ),
				'singular_name'			=> __( 'Passenger', 'my_theme' ),
				'search_items'			=> __( 'Search Passengers', 'my_theme' ),
				'popular_items'			=> __( 'Popular Passengers', 'my_theme' ),
				'all_items'				=> __( 'All Passengers', 'my_theme' ),
				'parent_item'			=> __( 'Parent Passenger', 'my_theme' ),
				'parent_item_colon'		=> __( 'Parent Passenger', 'my_theme' ),
				'edit_item'				=> __( 'Edit Passenger', 'my_theme' ),
				'update_item'			=> __( 'Update Passenger', 'my_theme' ),
				'add_new_item'			=> __( 'Add New Passenger', 'my_theme' ),
				'new_item_name'			=> __( 'New Passenger Name', 'my_theme' ),
				'add_or_remove_items'	=> __( 'Add or remove Passengers', 'my_theme' ),
				'choose_from_most_used'	=> __( 'Choose from most used', 'my_theme' ),
				'menu_name'				=> __( 'Passengers', 'my_theme' ),
			),
			'show_admin_column'  => true,
		    'show_ui'            => 'edit-tags.php?taxonomy=passengers&post_type=routes',
		    'show_in_quick_edit' => false,
		    'meta_box_cb'        => false
		) );
	}
}

RBT_PostTypes::init();