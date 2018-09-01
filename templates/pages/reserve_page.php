<?php
/*
 * Template Name: Reserve ticket
 * Description: Template reserve ticket.
  */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_ReserveTicketTemplate {
	protected static $_client_route_data;

	public static function init () {
		$form_data = array();
		$form_errors_num = 0;

		self::$_client_route_data = self::get_client_route_data();

	    if ( isset($_POST['submit'] ) ) {
			$form_data = array(
								'name' => sanitize_text_field($_POST['rbt-name']),
								'surname' => sanitize_text_field($_POST['rbt-surname']),
								'email' => sanitize_text_field($_POST['rbt-email']),
								'phone' => sanitize_text_field($_POST['rbt-phone']),
								'city' => sanitize_text_field($_POST['rbt-city']),
								'address' => sanitize_text_field($_POST['rbt-address']),
								'message' => sanitize_text_field($_POST['rbt-message']),
								'terms' => sanitize_text_field($_POST['terms'])
			);

			$validate_form = self::validation_form( $form_data );
			$form_errors_num = count( $validate_form->get_error_messages() );

			if ( $form_errors_num == 0 ) {
				self::insert_ticket_reservation( $form_data );
			}
		}

		$check_client_data = RBT_PageTemplates::get_routes(
								array(
										'route_departure_location' => self::$_client_route_data['route_departure_location'],
										'route_arrival_location' => self::$_client_route_data['route_arrival_location']
									) );

		if( !isset( $check_client_data ) ) {
	    	wp_safe_redirect( wp_get_referer() );
	    } else {
			get_header();

			if ( $form_errors_num > 0 ) {
			    foreach ( $validate_form->get_error_messages() as $error ) {
			        echo '<strong>ERROR</strong>: ' . $error . '<br />';
			    }
			}

    		self::reserve_form( $form_data );

			get_footer();
    	}
    }

	private static function validation_form ( $form_data ) {
		$form_errors = new WP_Error;

		if ( isset($_POST['g-recaptcha-response'] ) ) {
			$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". get_option('rbt-recaptcha-private-key') ."&response=". $_POST['g-recaptcha-response']);
			$response = json_decode($response["body"], true);

			if ( false == $response['success'] ) {
				$form_errors->add( 'captcha_invalid', 'Please check captcha!' );
			}
		} else {
			$form_errors->add( 'captcha_invalid', 'Please enable JavaScript in your browser!' );
		}

	    if ( ! validate_username( $form_data['name'] ) ) {
	    	$form_errors->add( 'name_invalid', 'Sorry, the name you entered is not valid' );
		}

	    if ( ! validate_username( $form_data['surname'] ) ) {
	    	$form_errors->add( 'surname_invalid', 'Sorry, the surname you entered is not valid' );
		}

		if ( !is_email( $form_data['email'] ) ) {
		    $form_errors->add( 'email_invalid', 'Email is not valid' );
		}

		if ( ! preg_match( '/^(?!(?:\d*-){5,})(?!(?:\d* ){5,})\+?[\d- ]+$/', $form_data['phone'] ) ) {
		    $form_errors->add( 'phone_invalid', 'Phone is not valid' );
		}

		if ( strlen( $form_data['city'] ) < 4) {
	    	$form_errors->add( 'city_length', 'Please type your city' );
	    }

		if ( strlen( $form_data['address'] ) < 7) {
	    	$form_errors->add( 'address_length', 'Please type your address' );
	    }

		if ( $form_data['terms'] != 'on' ) {
	    	$form_errors->add( 'terms_length', 'To continue please agree with the terms' );
	    }

	    return $form_errors;
	}

	private static function get_client_route_data() {
		return array(
						'route_departure_location' => RBT_Cookie::get_cookie('route_departure_location'),
						'route_arrival_location' => RBT_Cookie::get_cookie('route_arrival_location'),
						'route_date' => RBT_Cookie::get_cookie('route_date'),
						'route_departure_time' => RBT_Cookie::get_cookie('route_departure_time'),
						'route_arrival_time' => RBT_Cookie::get_cookie('route_arrival_time'),
						'route_price' => (int) RBT_Cookie::get_cookie('route_price'),
						'route_passengers' => (int) RBT_Cookie::get_cookie('route_passengers')
		);
	}

	private static function insert_ticket_reservation($data) {
		$post_id = wp_insert_post(
			array (
					   'post_type' => 'reservations',
					   'post_status' => 'pending',
					   'comment_status' => 'closed',
					   'ping_status' => 'closed'
					)
		);

		if($post_id) {
			require_once( RBT_ABSPATH . 'inc/class/rbt-email.php' );
			require_once( RBT_ABSPATH . 'inc/class/rbt-encryption.php' );

			$encrypt = RBT_Encrypt::encrypt($data['email'] . '///' . self::$_client_route_data['route_departure_location'] . '///' . self::$_client_route_data['route_arrival_location'] . '///' . get_the_date('Y-m-d H:i:s', $post_id));
			$link_confirmation = site_url() . '/reservation-confirmed/' . $encrypt;
			$send_copy = get_option('rbt-email-smtp-receive-on');

			if(isset($send_copy)) {
				$reservation_registred_message = $data['name'] . ' ' . $data['surname'] . ' a facut rezervare pentru ' . $data['route_passengers'] . ' persoane in data de ' . $data['route_date'] . ', la cursa ' . $data['route_departure_location'] . ' - ' . $data['route_arrival_location'] . ', la pretul de ' . $data['price'] . ' LEI.';

				RBT_Email::send_email($send_copy, 'New reservation registred', $reservation_registred_message);
			}

			RBT_Email::send_email($data['email'], 'Confirm reservation', $link_confirmation);

			add_post_meta($post_id, 'customer-name', $data['name']);
			add_post_meta($post_id, 'customer-surname', $data['surname']);
			add_post_meta($post_id, 'customer-email', $data['email']);
			add_post_meta($post_id, 'customer-phone', $data['phone']);
			add_post_meta($post_id, 'customer-city', $data['city']);
			add_post_meta($post_id, 'customer-address', $data['address']);

			if(isset($data['message'])) {
				add_post_meta($post_id, 'customer-message', $data['message']);
			}

			foreach (self::$_client_route_data as $key => $value) {
				add_post_meta($post_id, $key, $value);

				//RBT_Cookie::unset_cookie($key);
			}

			wp_redirect( site_url() . '/reservation-registred' );
		}
	}

	private static function reserve_form( $data ) { ?>
			<form method="post" name="reserve">
				<div class="content">
					<div class="contact_left_a">
						<p><strong>Nume*</strong></p>
						<div class="form_row">
							<div class="short_row"><input id="name" class="required" name="rbt-name" type="text" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" /></div>
						</div>
					</div>
					<div class="contact_left_b">
						<p><strong>Prenume*</strong></p>
						<div class="form_row">
							<div class="short_row"><input id="surname" class="required" name="rbt-surname" type="text" value="<?php echo isset($data['surname']) ? $data['surname'] : ''; ?>" /></div>
						</div>
					</div>
					<div class="clr"></div>
					<div class="contact_left_a">
						<p><strong>E-mail*</strong></p>
						<div class="form_row">
							<div class="short_row"><input id="email" class="required" name="rbt-email" type="text" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" /></div>
						</div>
					</div>
					<div class="contact_left_b">
						<p><strong>Telefon*</strong></p>
						<div class="form_row">
							<div class="short_row"><input id="phone" name="rbt-phone" type="text" value="<?php echo isset($data['phone']) ? $data['phone'] : ''; ?>" /></div>
						</div>
					</div>
					<div class="clr"></div>
					<div class="contact_left_a">
						<p><strong>Oraş*</strong></p>
						<div class="form_row">
							<div class="short_row"><input id="city" class="required" name="rbt-city" type="text" value="<?php echo isset($data['city']) ? $data['city'] : ''; ?>" /></div>
						</div>
					</div>
					<div class="contact_left_b">
						<p><strong>Adresa*</strong></p>
						<div class="form_row">
							<div class="short_row"><input id="address" class="required" name="rbt-address" type="text" value="<?php echo isset($data['address']) ? $data['address'] : ''; ?>" /></div>
						</div>
					</div>
					<div class="clr"></div>
					<div class="area_text_wrapper">
						<p><strong>Alte detalii</strong></p>
						<textarea id="area_text" name="rbt-message" cols="35" rows="5"><?php echo isset($data['message']) ? $data['message'] : ''; ?></textarea>
					</div>
					<div id="under_details_wrapper">
						<p><strong>Cod de securitate*</strong></p>
						<div class="clr"></div>
						<div class="form_row">
						</div>
						<div class="clr"></div>
						<div class="checbox_container">
							<div class="form_row">
								<input id="terms" name="terms" type="checkbox" />
								<label class="checkbox_terms">Am citit şi sunt de acord cu <a target="_self" style="text-decoration:underline;" href="javascript:void window.open('http://www.transport-focsani-aeroport.ro/termeni-si-conditii.html','','scrollbars=yes,menubar=yes,resizable=yes,left=30,top=30,height=800,width=1050')">Termenii şi condiţiile</a>.*</label>
							</div>
						</div>
						<div class="button_wrapper">
							<div class="g-recaptcha" data-sitekey="<?php echo get_option('rbt-recaptcha-public-key'); ?>"></div>
							<input name="submit" value="Rezerva bilet" type="submit">
						</div>
					</div>
				</div>
			</form>
<?php
	}
}

RBT_ReserveTicketTemplate::init();
?>