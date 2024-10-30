<?php

class IAPoster_Option_Token {

	private static $name = 'iaposter_token';

	/**
	 * @return string
	 */
	public static function get() {
		$value = get_option( self::$name );
		$value = $value != false ? $value : '';
		return $value;
	}

	public static function update( $val ) {
		update_option( self::$name, $val );
	}
}