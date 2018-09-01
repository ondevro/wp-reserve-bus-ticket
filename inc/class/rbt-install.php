<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_Install {
	public static function install() {
		if ( 'yes' === get_transient( 'rbt_installing' ) ) {
			return;
		}

		set_transient( 'rbt_installing', 'yes', MINUTE_IN_SECONDS * 10 );

		//self::create_tables();
		self::setup_environment();
	}

	private static function setup_environment() {
		RBT_PostTypes::register_post_types();
		RBT_PostTypes::register_taxonomies();

		RBT_PageGenerator::get_instance();
		RBT_PageGenerator::create_page();

		RBT_RewriteRules::add_rules();
		flush_rewrite_rules();
	}

	/*private static function create_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( self::get_schema() );
	}

	private static function get_schema() {
		global $wpdb;

		$tables = array();
		$collate = '';
		$tables['routes'] = $wpdb->prefix . 'routes';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		//if($wpdb->get_var( "show tables like '$tables['routes']'" ) != $tables['routes']) {
			$tables = "
						CREATE TABLE {$tables['routes']} (
		                id int NOT NULL AUTO_INCREMENT,
		                start char(255) NOT NULL,
		                destination char(255) NOT NULL,
		                route_time datetime NOT NULL,
		                status ENUM('0', '1') NOT NULL default '1',
						PRIMARY KEY  (id)
						) $collate;
					";
		//}

		return $tables;
	}*/
}