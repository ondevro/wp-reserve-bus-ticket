<?php
/*
 * Template Name: Reservation confirmed
 * Description: Template reserve ticket.
  */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $wp_query;

$wp_query->is_404 = false;
status_header( '200' );

get_header();

require_once(RBT_ABSPATH . 'inc/class/rbt-encryption.php');
require_once(RBT_ABSPATH . 'inc/class/rbt-templates.php');

$decrypted_data = RBT_Encrypt::decrypt($wp_query->query_vars['confirm']);
echo var_dump($decrypted_data);
$separate_data = explode('///', $decrypted_data);

$check_reservation = RBT_PageTemplates::check_reservation($separate_data);

if($check_reservation->have_posts()) {
	$confirm_reservation = wp_update_post( array (
						        'ID'          =>  $check_reservation->post->ID,
						        'post_status' =>  'publish'
        					) );

	echo 'RESERVATION CONFIRMED';
}

$page = get_posts(
    array(
        'name'      => 'reservation-confirmed',
        'post_type' => 'page'
    )
);

if ( $page ) {
    echo $page[0]->post_content;
} else {
	echo 'One more step left, please confirm reservation by accesing the link received on your email. Thank you for time!';
}

get_footer();