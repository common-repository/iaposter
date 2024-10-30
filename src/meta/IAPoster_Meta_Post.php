<?php

class IAPoster_Meta_Post {

	private static $option_name = 'iaposter_meta';

	static function get( $post_id ) {
		$db_meta = get_post_meta( $post_id, self::$option_name, true );

		return ! $db_meta ? self::get_default() : array_merge( self::get_default(), $db_meta );
	}

	private static function get_default() {
		return array(
			'board_id'               => '0',
			'description'            => '',
			'use_custom_description' => false
		);
	}

	static function update( $post_id, $new_value ) {
		$final_meta = array_merge( self::get_default(), $new_value );
		update_post_meta( $post_id, self::$option_name, $final_meta );
	}
}