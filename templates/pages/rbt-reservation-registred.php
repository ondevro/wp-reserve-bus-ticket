<?php
/*
 * Template Name: Reserve ticket
 * Description: Template reserve ticket.
  */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $wp_query;

$wp_query->is_404 = false;
status_header( '200' );

get_header();

$page = get_posts(
    array(
        'name'      => 'reservation-registred',
        'post_type' => 'page'
    )
);

if ( $page ) {
    echo $page[0]->post_content;
} else {
	echo 'One more step left, please confirm reservation by accesing the link received on your email. Thank you for time!';
}

get_footer();