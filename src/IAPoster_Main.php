<?php

class IAPoster_Main {

	private $file;
	private $version;

	function __construct( $file, $version, $slug ) {
		$this->file    = $file;
		$this->version = $version;
		$this->slug    = $slug;

		$this->init();
		$this->add_actions();
	}

	private function add_actions() {
		add_action( 'init', array( $this, 'update_plugin' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'transition_post_status', array( $this, 'status_transition' ), 10, 3 );
		add_action( 'deleted_post', array( $this, 'deleted_post' ) );
	}

	function admin_notices() {
		$this->not_authorized_notice();
	}

	private function not_authorized_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$token        = IAPoster_Option_Token::get();
		$page         = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
		if ( '' === $token && $page != $this->slug . '_settings' ) {
			$notice = new IAPoster_UI_AdminNotice( 'error', true, sprintf(
				__( '<b>Image Auto Poster</b>: Your Pinterest account is not authorized. <a class="button button-primary" href="%s">Authorize my account &rarr;</a>' ),
				admin_url( 'options-general.php?page=' . $this->slug . '_settings' )
			) );
			echo $notice->get_html();
		}
	}

	private function init() {
		$cron = IAPoster_Cron::get_instance();

		if ( is_admin() ) {
			new IAPoster_WelcomeScreen( $this->file, $this->slug, $this->version );
			new IAPoster_Screen_PostEdit( $this->file, $this->version, $this->slug );
			new IAPoster_Screen_Settings( $this->file, $this->version, $this->slug );
		}
	}

	function deleted_post( $post_id ) {
		$queue_service = new IAPoster_DB_QueueService();
		$queue_service->delete_by_post_id( $post_id );
	}

	/**
	 * @param $new_status string
	 * @param $old_status string
	 * @param $post WP_Post
	 */
	function status_transition( $new_status, $old_status, $post ) {
		if ( $new_status != $old_status ) {
			$queue_service = new IAPoster_DB_QueueService();
			$rows_updated  = $queue_service->update_post_status( $post->ID, $new_status );
			if ( $rows_updated > 0 ) {
				$cron = IAPoster_Cron::get_instance();
				$cron->schedule_pinning();
			}
		}
	}

	function update_plugin() {
		$updater = new IAPoster_Update_Version( $this->version, $this->slug );
		$updater->run_updates();

		//new IAPoster_Update_Download( $this->file, $this->version );
	}
}