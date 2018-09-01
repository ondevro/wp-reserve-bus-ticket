<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class RBT_RewriteRules {
    public static function init() {
        add_action('init', array( __CLASS__, 'add_rules' ), 10, 0);
        add_filter('template_redirect', array( __CLASS__, 'products_plugin_display') );
    }

    public static function add_rules() {
        add_rewrite_tag('%confirm%', '([^&]+)');
        add_rewrite_tag('%route_departure_location%', '([^&]+)');
        add_rewrite_tag('%route_arrival_location%', '([^&]+)');
        add_rewrite_tag('%route_passengers%', '([^&]+)');
        add_rewrite_tag('%route_date%', '([^&]+)');

        add_rewrite_rule('^reservation-confirmed/([^/]*)/?', 'index.php?pagename=reservation-confirmed&confirm=$matches[1]', 'top');
        add_rewrite_rule('^ticket/([^/]*)/([^/]*)/([^/]*)/([^/]*)/?', 'index.php?pagename=ticket&route_departure_location=$matches[1]&route_arrival_location=$matches[2]&route_passengers=$matches[3]&route_date=$matches[4]', 'top');
    }

    public static function products_plugin_display() {
        $pagename = get_query_var('pagename');

        if ( 'reservation-registred' == $pagename ) {
            require_once( RBT_ABSPATH . 'templates/pages/rbt-reservation-registred.php' );
            exit;
        } elseif ( 'reservation-confirmed' == $pagename ) {
            require_once( RBT_ABSPATH . 'templates/pages/rbt-reservation-confirmed.php' );
            exit;
        }
    }
}

RBT_RewriteRules::init();