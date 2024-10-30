<?php

class IAPoster_Cron {

	private $slug;
	private $interval_name;
	private $pin_images_hook;
	private $clear_logs_hook;

	private static $instance = null;
	private $log_service;

	private function __construct() {
		$this->slug                    = 'iaposter';
		$this->interval_name           = $this->slug . '_minute';
		$this->pin_images_hook         = $this->slug . '_pin_images_cron_hook';
		$this->clear_logs_hook         = $this->slug . '_clear_logs_hook';
		$this->log_service             = new IAPoster_DB_LogService();

		add_action( $this->pin_images_hook, array( $this, 'pin_image_debug' ) );
		add_action( $this->clear_logs_hook, array( $this, 'clear_logs' ) );
		add_filter( 'cron_schedules', array( $this, 'add_cron_interval' ) );

		if ( '1' === get_transient( 'iaposter_schedule_jobs' ) ) {
			$this->schedule_pinning();
			$this->schedule_clearing_logs();
			delete_transient( 'iaposter_schedule_jobs' );
		}
	}

	static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new IAPoster_Cron();
		}

		return self::$instance;
	}

	function add_cron_interval( $schedules ) {
		$schedules[ $this->interval_name ] = array(
			'interval' => 60,
			'display'  => esc_html__( 'Every Minute', 'iaposter' ),
		);

		return $schedules;
	}

	function clear_logs() {
		$action_name = 'Cron Clear Logs';
		$this->log_service->insert( IAPoster_DB_Log::create_info( $action_name, __( 'Start', 'iaposter' ) ) );
		$options = new IAPoster_Option_Settings();
		$val     = $options->get();
		$days    = $val['delete_logs_after'];
		$deleted = $this->log_service->delete_older_than_x_days( $days );
		if ( false === $deleted ) {
			global $wpdb;
			$this->log_service->insert( IAPoster_DB_Log::create_error( $action_name, $wpdb->last_error ) );
		} else {
			$this->log_service->insert( IAPoster_DB_Log::create_info( $action_name, sprintf( __( 'Deleted %s items.', 'iaposter' ), $deleted ) ) );
		}
		$this->log_service->insert( IAPoster_DB_Log::create_info( $action_name, __( 'Finish', 'iaposter' ) ) );
	}

	function pin_image() {
		$action_name = 'Cron Pin Image';
		$token       = IAPoster_Option_Token::get();

		if ( '' === $token ) {
			$this->log_service->insert( IAPoster_DB_Log::create_error( $action_name, __( 'Pinterest account not authorized. Go to settings and authorize your account.', 'iaposter' ) ) );
			$this->unschedule_pinning();
			return;
		}

		$queue_service = new IAPoster_DB_QueueService();
		$image_to_pin  = $queue_service->get_next_entry_to_process();

		if ( null === $image_to_pin ) {
			$this->log_service->insert( IAPoster_DB_Log::create_info( $action_name, __( 'No images to Pin.', 'iaposter' ) ) );
			$this->unschedule_pinning();
			return;
		}

		$post_meta    = IAPoster_Meta_Post::get( $image_to_pin->post_id );
		$post_desc = $post_meta['use_custom_description'] ? $post_meta['description'] : false;
		$options_main = new IAPoster_Option_Settings();
		$options      = $options_main->get();
		$board_id     = $this->get_pin_board_id( $image_to_pin->board_id, $post_meta['board_id'], $options['default_board'], $options['categories_to_boards'], $image_to_pin->post_id );

		if ( !$this->is_valid_board_id( $board_id ) ) {
			$this->log_service->insert( IAPoster_DB_Log::create_error( $action_name, __( 'No default board selected. Please select default board in settings.', 'iaposter' ) ) );
			$this->unschedule_pinning();

			return;
		}
		$post_url           = get_permalink( $image_to_pin->post_id );
		$src                = $image_to_pin->src;
		$description_format = $this->get_pin_description_format( $image_to_pin->description, $post_desc, $options['description'] );
		$content            = get_post_field( 'post_content', $image_to_pin->post_id );
		$content            = apply_filters( 'the_content', $content );
		$img_attributes     = IAPoster_Lib_HtmlParser::get_image_attributes_by_src( $image_to_pin->src, $content );
		$post_attributes    = IAPoster_Lib_PostHelper::get_post_attributes( $image_to_pin->post_id );
		
		$description        = IAPoster_Lib_DescriptionProcessor::process( $description_format, array_merge( $img_attributes, $post_attributes ) );
		$pinterest_service = new IAPoster_Pinterest_Service();
		$pinning_result    = $pinterest_service->pinImage( $token, $src, $description, $post_url, $board_id );
		$queue_service->mark_as_pinned( $image_to_pin->id, $pinning_result['id'], $pinning_result['url'] );
		$this->log_service->insert( IAPoster_DB_Log::create_info(
			$action_name,
			sprintf( __( '<a href="%s" target="_blank">Image pinned</a>', 'iaposter' ), $pinning_result['url'] )
		) );
	}

	function pin_image_debug() {
		try {
			$this->pin_image();
		} catch( Exception $err ) {
			$this->log_service->insert( IAPoster_DB_Log::create_error( 'Cron Pin Image', $err->getMessage() ) );
			$this->unschedule_pinning();
		}
	}

	/**
	 * Gets first available board id checking the following in order of importance: Pin's board id, Post's board id, Category board id and default board id.
	 *
	 * @param $pin_board_id
	 * @param $post_board_id
	 * @param $default_board_id
	 * @param $post_id
	 *
	 * @return mixed
	 */
	private function get_pin_board_id( $pin_board_id, $post_board_id, $default_board_id, $categories_to_boards, $post_id ) {
		if ( $this->is_valid_board_id( $pin_board_id )  ) {
			return $pin_board_id;
		}
		if ( $this->is_valid_board_id( $post_board_id ) ) {
			return $post_board_id;
		}

		$cats = wp_get_post_categories( $post_id, array( 'fields' => 'ids' ) );
		foreach ( $cats as $cat ) {
			foreach( $categories_to_boards as $category_board ) {
				if ($category_board[ 'catId' ] === (string)$cat) {
					return $category_board[ 'boardId' ];
				}
			}
		}

		return $default_board_id;
	}

	private function is_valid_board_id( $id ) {
		return $id != '0' && !empty( $id );
	}

	private function get_pin_description_format( $pin_description, $post_description, $default_description ) {
		if ( '' != $pin_description ) {
			return $pin_description;
		}

		return false === $post_description ? $default_description : $post_description;
	}

	public function is_pinning_scheduled() {
		return wp_next_scheduled( $this->pin_images_hook ) != false;
	}

	public function schedule_pinning() {
		if ( ! $this->is_pinning_scheduled() ) {
			$this->log_service->insert( IAPoster_DB_Log::create_info( 'Cron Schedule Pinning' ) );
			wp_schedule_event( time(), $this->interval_name, $this->pin_images_hook );
		}
	}

	public function unschedule_pinning() {
		$timestamp = wp_next_scheduled( $this->pin_images_hook );
		wp_unschedule_event( $timestamp, $this->pin_images_hook );
		$this->log_service->insert( IAPoster_DB_Log::create_info( 'Cron Unschedule Pinning' ) );
	}

	function schedule_clearing_logs() {
		if ( ! wp_next_scheduled( $this->clear_logs_hook ) ) {
			wp_schedule_event( time(), 'daily', $this->clear_logs_hook );
		}
	}
}