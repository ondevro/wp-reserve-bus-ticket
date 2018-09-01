<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RBT_Encrypt {
	private static $_key = 'qJB0rGtIn5UB1xG03efyCp';
	private static $_dirty = array("+", "/", "=");
	private static $_clean = array("-", "_", ",");

	public static function encrypt ( $pure_string ) {
    	$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB );
    	$iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );

	    $encrypted_string = base64_encode( mcrypt_encrypt ( MCRYPT_BLOWFISH, self::$_key, utf8_encode ( $pure_string ), MCRYPT_MODE_ECB, $iv ) );

	    return str_replace( self::$_dirty, self::$_clean , $encrypted_string);
	}

	public static function decrypt ( $encrypted_string ) {
    	$iv_size = mcrypt_get_iv_size( MCRYPT_BLOWFISH, MCRYPT_MODE_ECB );
    	$iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );

	    $string = base64_decode( str_replace( self::$_clean, self::$_dirty, $encrypted_string ) );

	    return mcrypt_decrypt( MCRYPT_BLOWFISH, self::$_key, $string, MCRYPT_MODE_ECB, $iv );
	}
}