<?php

class IAPoster_Option_Settings extends IAPoster_Option_Base {

	function get_default_options() {
		$defaults = array(
			'add_all_images'       => false,
			'categories_to_boards' => array(),
			'check_shortcodes'     => false,
			'default_board'        => '0',
			'delete_all_data_on_uninstall' => true,
			'delete_logs_after'    => 14,
			'description'          => '{[image_title]|[image_alt]}',
		);

		return $defaults;
	}

	function get_option_name() {
		return 'iaposter_options';
	}

	function get_types() {
		return array(
			'add_all_images'       => 'boolean',
			'categories_to_boards' => 'array',
			'check_shortcodes'     => 'boolean',
			'default_board'        => 'string',
			'delete_all_data_on_uninstall' => 'boolean',
			'delete_logs_after'    => 'int',
			'description'          => 'string',
		);
	}

	function sanitize($input) {
		$input = parent::sanitize( $input );
		$input['check_shortcodes'] = false;
		return $input;
	}
}