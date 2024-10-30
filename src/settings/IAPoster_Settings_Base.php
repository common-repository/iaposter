<?php

abstract class IAPoster_Settings_Base {

	/**
	 * @var IAPoster_Option_Base
	 */
	protected $options;

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * @var []
	 */
	protected $notices;

	/**
	 * IAPoster_Settings_Base constructor.
	 *
	 * @param $slug
	 * @param $options IAPoster_Option_Base
	 */
	function __construct( $slug, $options ) {
		$this->slug    = $slug;
		$this->options = $options;
		$this->notices = array();

		add_action( 'admin_notices', array( $this, 'show_notices' ) );
	}

	function get_settings_configuration() {
		return array();
	}

	function get_settings_i18n() {
		return array(
			'submit' => __( 'Save Changes', 'iaposter' ),
		);
	}

	function get_slug() {
		return $this->slug;
	}

	function show_notices() {
		foreach ( $this->notices as $notice ) {
			echo $notice->get_html();
		}
	}

	function save_settings( $settings ) {
		$validator = new IAPoster_Settings_Validator( $settings, $this->options->get_default_options(), $this->get_settings_configuration() );
		$errors    = $validator->get_errors();
		if ( count( $errors ) > 0 ) {
			$error_messages  = array_merge(
				array( '<strong>' . __( 'Settings not saved.', 'iaposter' ) . '</strong>' ),
				$errors
			);
			$this->notices[] = new IAPoster_UI_AdminNotice( 'error', true, join( '<br/>', $error_messages ) );
			return false;
		} else {
			$sanitized = $validator->get_result();
			$sanitized = $this->options->sanitize( $sanitized );
			$this->options->update( $sanitized );
			$this->notices[] = new IAPoster_UI_AdminNotice( 'success', true, '<strong>' . __( 'Settings saved.', 'iaposter' ) . '</strong>' );
			return true;
		}
	}
}