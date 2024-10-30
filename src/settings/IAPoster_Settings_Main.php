<?php

class IAPoster_Settings_Main extends IAPoster_Settings_Base {

	private $authorize_action;
	private $default_board;
	private $revoke_action;
	private $service_status_action;
	private $pinterest_service;

	function __construct( $slug ) {
		parent::__construct( 'main', new IAPoster_Option_Settings() );
		$this->default_board     = array( '0' => __( 'Choose Board', 'iaposter' ) );
		$this->authorize_action  = $slug . '_authorize_pinterest_app';
		$this->revoke_action     = $slug . '_revoke_pinterest_app';
		$this->service_status_action = $slug . '_service_status';
		$this->pinterest_service = new IAPoster_Pinterest_Service();

		add_action( 'wp_ajax_' . $this->authorize_action, array( $this, 'authorize_app' ) );
		add_action( 'wp_ajax_' . $this->revoke_action, array( $this, 'revoke_app' ) );
		
	}

	function get_settings_i18n() {
		$login_url = $this->pinterest_service->getLoginUrl( admin_url( 'options-general.php?page=iaposter_settings&tab=settings' ) );

		$i18n   = array(
			'authorization' => array(
				'label'                  => __( 'Pinterest authorization', 'iaposter' ),
				'action_url'             => admin_url( 'admin-ajax.php' ),
				'authorize_action_name'  => $this->authorize_action,
				'authorize_action_nonce' => wp_create_nonce( $this->authorize_action ),
				'revoke_action_name'     => $this->revoke_action,
				'revoke_action_nonce'    => wp_create_nonce( $this->revoke_action ),
				'login_url'              => $login_url,
				'login_link'             => __( 'Authorize &rarr;', 'iaposter' ),
				'revoke_link'            => __( 'Revoke &rarr;', 'iaposter' ),
				'authorized'             => __( 'Authorized', 'iaposter' ),
				'unauthorized'           => __( 'Not authorized yet', 'iaposter' ),
			),
			'status' => array(
				'action_url'             => admin_url( 'admin-ajax.php' ),
				'action_name'  => $this->service_status_action,
				'action_nonce' => wp_create_nonce( $this->service_status_action ),
			),
			'sections'      => array(
				'pinterest'   => __( 'Pinterest settings', 'iaposter' ),
				'post_editor' => __( 'Post editor', 'iaposter' ),
				'misc'        => __( 'Misc', 'iaposter' )
			),
			'save_changes'  => __( 'Save Changes', 'iaposter' )
		);
		$parent = parent::get_settings_i18n();

		return array_merge( $parent, $i18n );
	}

	function get_module_settings() {
		return array(
			'slug' => 'settings',
			'name' => __( 'Settings', 'iaposter' ),
		);
	}

	function get_settings_configuration() {
		$token  = IAPoster_Option_Token::get();
		$boards = $this->default_board;
		$me_boards = $this->pinterest_service->getMeBoards( $token );
		foreach ( $me_boards as $index => $me_board ) {
			$boards[ $index ] = $me_board;
		}

		$option_value = $this->options->get();
		$res          = array();

		$res['add_all_images'] = array(
			'key'   => 'add_all_images',
			'label' => __( 'Check all images by default', 'iaposter' ),
			'type'  => 'boolean',
			'desc'  => __( ' Check this if you would like to have all images in the post editor selected to be pinned by default.', 'iaposter' )
		);

		$res['check_shortcodes'] = array(
			'key'   => 'check_shortcodes',
			'label' => __( 'Check for shortcodes', 'iaposter' ),
			'text'  => __( 'Check content for shortcodes', 'iaposter' ),
			'type'  => 'boolean',
			'desc'  => __( 'If you use shortcodes that add images in your content and want those images to be available, check this option. It works slower when enabled so do not check this if you don\'t need it.', 'iaposter' )
		);

		$res['default_board'] = array(
			'key'     => 'default_board',
			'label'   => __( 'Default Pinterest Board', 'iaposter' ),
			'options' => $boards,
			'type'    => 'select',
			'desc'    => __( 'Unless you specify a different board elsewhere, images will be pinned to this board.', 'iaposter' ),
		);

		$res['delete_all_data_on_uninstall'] = array(
			'key'   => 'delete_all_data_on_uninstall',
			'label' => __( 'Delete all data on uninstall', 'iaposter' ),
			'type'  => 'boolean',
			'desc'  => __( 'Check to delete all plugin data on uninstall. If you plan to reinstall the plugin, uncheck this setting.', 'iaposter' )
		);

		$res['delete_logs_after'] = array(
			'key'   => 'delete_logs_after',
			'label' => __( 'Delete logs after', 'iaposter' ),
			'type'  => 'int',
			'desc'  => __( 'Plugin\'s logs need to be deleted regularly.', 'iaposter' ),
			'unit'  => __( 'days', 'iaposter' ),
			'min'   => 0,
		);

		$res['description'] = array(
			'key'       => 'description',
			'label'     => __( 'Default description', 'iaposter' ),
			'type'      => 'string',
			'validator' => array( 'IAPoster_Lib_DescriptionProcessor', 'validate' ),
			'desc'      => sprintf(
				__( 'Default description of the Pin. Go <a href="%s" target="_blank">to the documentation</a> to read more.', 'iaposter' ),
				'https://highfiveplugins.com/iap/iap-documentation//#Default_description'
			)
		);


		foreach ( $res as $key => $setting ) {
			$res[ $key ]['value'] = $option_value[ $key ];
		}

		$res['authorized'] = array(
			'value' => '' !== $token
		);

		return $res;
	}

	public function authorize_app() {
		if ( ! check_admin_referer( $this->authorize_action ) ) {
			return;
		}
		$code = $_REQUEST['pinterest_code'];
		try {
			$token = $this->pinterest_service->getOAuthToken( $code );
			IAPoster_Option_Token::update( $token );
			$cron = IAPoster_Cron::get_instance();
			$cron->schedule_pinning();
			$me_boards = $this->pinterest_service->getMeBoards( $token );
			$boards = $this->default_board;
			foreach ( $me_boards as $index => $me_board ) {
				$boards[ $index ] = $me_board;
			}
			wp_send_json_success( array(
				'message' => sprintf( '<div class="updated">%s</div>', __( 'Pinterest authorization succeeded.', 'iaposter' ) ),
				'boards'  => $boards
			) );
		} catch ( Exception $e ) {
			wp_send_json_error( array(
				'message' => sprintf( '<div class="error">%s</div>', __( 'Pinterest authorization failed.', 'iaposter' ) )
			) );
		}
	}

	public function save_settings( $settings ) {
		$success                          = parent::save_settings( $settings );
		if ( $success ) {
			$cron = IAPoster_Cron::get_instance();
			$cron->schedule_pinning();
		}
	}


	public function revoke_app() {
		if ( ! check_admin_referer( $this->revoke_action ) ) {
			return;
		}
		IAPoster_Option_Token::update( '' );
		$settings                  = $this->options->get();
		$default_settings          = $this->options->get_default_options();
		$settings['default_board'] = $default_settings['default_board'];
		$this->options->update( $settings );
		wp_send_json_success( array(
			'message' => sprintf( '<div class="updated">%s</div>', __( 'Pinterest account disconected.', 'iaposter' ) ),
			'boards'  => $this->default_board
		) );
	}

}