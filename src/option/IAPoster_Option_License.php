<?php

class IAPoster_Option_License extends IAPoster_Option_Base {

	function get_default_options() {
		$defaults = array(
			'key' => '',
			'status' => 'invalid',
			'expires' => ''
		);

		return $defaults;
	}

	function get_option_name() {
		return 'iaposter_license';
	}

	function get_types() {
		return array(
			'key' => 'string',
			'status' => 'string',
			'expires' => 'string'
		);
	}
}