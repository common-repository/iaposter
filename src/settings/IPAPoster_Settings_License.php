<?php

class IAPoster_Settings_License extends IAPoster_Settings_Base {

	private $activate_action;
	private $deactivate_action;

	function __construct( $slug ) {
		parent::__construct( 'license', new IAPoster_Option_License() );
		$this->activate_action   = 'activate_license';
		$this->deactivate_action = 'deactivate_license';
	}

	function activate( $license_key ) {
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license_key,
			'item_name'  => urlencode( IAPoster_Update_Download::$item_name ),
			'url'        => home_url()
		);
		$response   = wp_remote_post( IAPoster_Update_Download::$store_url, array(
			'timeout'   => 15,
			'sslverify' => false,
			'body'      => $api_params
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$message = ( is_wp_error( $response ) && $response->get_error_message() )
				? $response->get_error_message()
				: __( 'An error occurred, please try again.', 'iaposter' );

			$this->notices[] = new IAPoster_UI_AdminNotice( 'error', true, $message );

			return;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		if ( false === $license_data->success ) {
			switch ( $license_data->error ) {
				case 'expired' :
					$message = sprintf(
						__( 'Your license key expired on %s.', 'iaposter' ),
						date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
					);
					break;
				case 'revoked' :
					$message = __( 'Your license key has been disabled.', 'iaposter' );
					break;
				case 'missing' :
					$message = __( 'Invalid license.', 'iaposter' );
					break;
				case 'invalid' :
				case 'site_inactive' :
					$message = __( 'Your license is not active for this URL.', 'iaposter' );
					break;
				case 'item_name_mismatch' :
					$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'iaposter' ), IAPoster_Update_Download::$item_name );
					break;
				case 'no_activations_left':
					$message = __( 'Your license key has reached its activation limit.', 'iaposter' );
					break;
				default :
					$message = __( 'An error occurred, please try again.', 'iaposter' );
					break;
			}
			$this->notices[] = new IAPoster_UI_AdminNotice( 'error', true, $message );

			return;
		}
		$new_value = array(
			'key'     => $license_key,
			'status'  => $license_data->license,
			'expires' => $license_data->expires
		);
		$this->options->update( $new_value );
		$this->notices[] = new IAPoster_UI_AdminNotice( 'success', true, __( 'License Activated.', 'iaposter' ) );
	}

	function deactivate() {
		$settings    = $this->options->get();
		$license_key = trim( $settings['key'] );

		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license_key,
			'item_name'  => urlencode( IAPoster_Update_Download::$item_name ),
			'url'        => home_url()
		);
		$response   = wp_remote_post( IAPoster_Update_Download::$store_url, array(
			'body'      => $api_params,
			'timeout'   => 15,
			'sslverify' => false
		) );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$message = ( is_wp_error( $response ) && $response->get_error_message() )
				? $response->get_error_message()
				: __( 'An error occurred, please try again.', 'iaposter' );

			$this->notices[] = new IAPoster_UI_AdminNotice( 'error', true, $message );

			return;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		$new_value    = array(
			'key'     => '',
			'status'  => 'invalid',
			'expires' => null
		);
		$this->options->update( $new_value );

		if ( $license_data->success ) {
			$this->notices[] = new IAPoster_UI_AdminNotice( 'success', true, __( 'License deactivated.', 'iaposter' ) );
		} else {
			$this->notices[] = new IAPoster_UI_AdminNotice( 'error', true, __( 'License deactivation failed.', 'iaposter' ) );
		}
	}

	function get_settings_i18n() {
		$parent                         = parent::get_settings_i18n();
		$i18n                           = array();
		$i18n['title']                  = __( 'Plugin License', 'iaposter' );
		$i18n['action']                 = __( 'Action', 'iaposter' );
		$i18n['activate']               = __( 'Activate License', 'iaposter' );
		$i18n['activate_action_name']   = $this->activate_action;
		$i18n['deactivate']             = __( 'Deactivate License', 'iaposter' );
		$i18n['deactivate_action_name'] = $this->deactivate_action;
		$i18n['active']                 = __( 'License active', 'iaposter' );

		return array_merge( $parent, $i18n );
	}

	function get_module_settings() {
		return array(
			'slug' => 'license',
			'name' => __( 'License', 'iaposter' ),
		);
	}

	function save_settings( $settings ) {
		if ( isset( $settings[ $this->activate_action ] ) ) {
			$validator = new IAPoster_Settings_Validator( $settings, $this->options->get_default_options(), $this->get_settings_configuration() );
			$errors    = $validator->get_errors();
			if ( count( $errors ) > 0 ) {
				$error_messages  = array_merge(
					array( '<strong>' . __( 'Settings not saved.', 'frizzly' ) . '</strong>' ),
					$errors
				);
				$this->notices[] = new IAPoster_UI_AdminNotice( 'error', true, join( '<br/>', $error_messages ) );
			} else {
				$sanitized = $validator->get_result();
				$sanitized = $this->options->sanitize( $sanitized );
				$this->options->update( $sanitized );
				$this->activate( $sanitized['key'] );
			}
		}

		if ( isset( $settings[ $this->deactivate_action ] ) ) {
			$this->deactivate();
		}
	}

	function get_settings_configuration() {
		$option_value = $this->options->get();
		$res          = array();

		$res['status'] = array(
			'key'  => 'status',
			'type' => 'string',
		);

		$res['expires'] = array(
			'key'   => 'expires',
			'type'  => 'string',
			'label' => '' != $option_value['expires']
				? sprintf(
					__( 'Expires %s', 'iaposter' ),
					date_i18n( get_option( 'date_format' ), strtotime( $option_value['expires'], current_time( 'timestamp' ) ) )
				)
				: ''
		);

		$res['key'] = array(
			'key'      => 'key',
			'label'    => __( 'License Key', 'iaposter' ),
			'type'     => 'string',
			'required' => true
		);

		foreach ( $res as $key => $setting ) {
			$res[ $key ]['value'] = $option_value[ $key ];
		}

		return $res;
	}

}