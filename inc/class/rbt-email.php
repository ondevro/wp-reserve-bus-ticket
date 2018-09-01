<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_Email {
	public static function smtp() {
		add_action( 'phpmailer_init', array( __CLASS__ , 'wp_smtp' ) );
	}

	public function wp_smtp($phpmailer) {
		$phpmailer->Mailer = 'smtp';
		$phpmailer->From = get_option('rbt-email-smtp-sent-from');
		$phpmailer->FromName = get_option('rbt-email-smtp-sent-from-name');
		$phpmailer->Sender = $phpmailer->From;
		$phpmailer->AddReplyTo($phpmailer->From, $phpmailer->FromName);
		$phpmailer->Host = get_option('rbt-email-smtp-host');
		$phpmailer->SMTPSecure = 'ssl';
		$phpmailer->Port = get_option('rbt-email-smtp-port');
		$phpmailer->Username = get_option('rbt-email-username');
		$phpmailer->Password = get_option('rbt-email-password');
	}

	public static function send_email($to, $subject, $message) {
		wp_mail($to, $subject, $message);
	}
}

RBT_Email::smtp();