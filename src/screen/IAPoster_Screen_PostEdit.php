<?php

class IAPoster_Screen_PostEdit {

	private $file;
	private $slug;
	private $version;
	private $nonce_name;
	private $save_post_action;
	private $queue_service;

	public function __construct( $file, $version, $slug ) {
		$this->file                = $file;
		$this->version             = $version;
		$this->slug                = $slug;
		$this->save_post_action    = $slug . '_post_edit';
		$this->nonce_name 		   = $slug . '_ajax_post_edit';
		$this->queue_service       = new IAPoster_DB_QueueService();

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		
		add_action( 'wp_ajax_' . $slug . '_get_content', array( $this, 'get_parsed_content' ) );
		add_action( 'wp_ajax_' . $slug . '_get_status', array( $this, 'get_status' ) );
		add_action( 'wp_ajax_' . $slug . '_remove_image', array( $this, 'remove_image' ) );
	}

	function add_meta_boxes() {
		add_meta_box(
			'iaposter_edit',
			__( 'Image Auto Poster', 'iaposter' ),
			array( $this, 'render_meta_box' ),
			array( 'post' ),
			'side',
			'high'
		);
	}

	function enqueue_scripts( $screen_id ) {
		global $typenow;
		$screens = array( 'post-new.php', 'post.php' );
		$types = array( 'post' );
		if ( ! in_array( $screen_id, $screens ) || ! in_array( $typenow, $types ) ) {
			return;
		}
		$option      = new IAPoster_Option_Settings();
		$pin_service = new IAPoster_Pinterest_Service();
		$boards      = $pin_service->getMeBoards( IAPoster_Option_Token::get() );
		$boards      = array( '0' => __( 'Default board', 'iaposter' ) ) + $boards;
		$post_meta   = IAPoster_Meta_Post::get( get_the_ID() );

		wp_enqueue_style( $this->slug . 'postedit-style', plugin_dir_url( $this->file ) . 'css/post-edit.css', array(), $this->version );
		wp_enqueue_script( $this->slug . 'post_edit', plugin_dir_url( $this->file ) . '/js/post_edit.min.js', array( 'jquery' ), $this->version );
		wp_localize_script( $this->slug . 'post_edit', $this->slug . 'post_edit', array(
			'ajaxurl'      => admin_url( 'admin-ajax.php' ),
			'boards'       => $boards,
			'images'       => $this->queue_service->get_for_post( get_the_ID() ),
			'slug'		   => $this->slug,
			'nonce'		   => wp_create_nonce( $this->nonce_name ),
			'meta'     	   => $post_meta,
			'settings'     => $option->get(),
			'post_id'      => get_the_ID(),
			'i18n'         => array(
				'add_to_queue_title'           => __( 'Add image to queue', 'iaposter' ),
				'board_label'                  => __( 'Board', 'iaposter' ),
				'check_all'                    => __( 'Check all', 'iaposter' ),
				'use_custom_description_label' => __( 'Use custom description', 'iaposter' ),
				'description_label'            => __( 'Description', 'iaposter' ),
				'description_error'            => $this->get_description_error( $post_meta['description'] ),
				'in_queue_title'               => __( 'Image in queue', 'iaposter' ),
				'remove_from_queue_title'      => __( 'Remove image from queue', 'iaposter' ),
				'image_pinned_title'           => __( 'Image pinned', 'iaposter' ),
				'images_title'                 => __( 'Choose images to Pin', 'iaposter' ),
				'settings_title'               => __( 'Settings', 'iaposter' ),
			)
		) );
	}

	private function get_description_error( $desc_value ) {
		return IAPoster_Lib_DescriptionProcessor::validate( $desc_value ) ? '' : __('Default description isn\'t properly formatted.', 'iaposter');;
	}

	/**
	 * @param $post WP_Post
	 */
	function render_meta_box( $post ) {
		$token_value = IAPoster_Option_Token::get();
		if ( '' === $token_value ) {
			echo sprintf(
				__( '<p>You need to authorize your Pinterest account before you can Pin images.</p> <a class="button button-primary" href="%s">Go authorize &rarr;</a>' ),
				admin_url( 'options-general.php?page=' . $this->slug . '_settings' )
			);

			return;
		}
		?>
        <input type="hidden" name="<?php echo $this->save_post_action; ?>"
               value="<?php echo wp_create_nonce( $this->save_post_action ); ?>"/>
        <div id="iaposter-container"></div>
		<?php
	}

	function save_post( $post_id ) {
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			|| wp_is_post_revision( $post_id )
			|| ! isset( $_POST[ $this->save_post_action ] )
			|| ! check_admin_referer( $this->save_post_action, $this->save_post_action ) ) return;

		$meta = array(
			'board_id'               => $_POST['iaposter_board_id'],
			'description'            => isset( $_POST['iaposter_use_custom_description'] ) ? $_POST['iaposter_description'] : null,
			'use_custom_description' => isset( $_POST['iaposter_use_custom_description'] )
		);
		IAPoster_Meta_Post::update( $post_id, $meta );

		if ( !isset( $_POST['iaposter_images'] ) || !is_array( $_POST['iaposter_images'] ) ) return;

		$images        = $_POST['iaposter_images'];
		$images_to_add = array();
		foreach ( $images as $img ) {
			$img = stripslashes( $img );
			$img = json_decode( $img, true );
			if ( ! isset( $img['id'] ) ) {
				$images_to_add[] = $img;
			}
		}
		$post = get_post( $post_id );

		foreach ( $images_to_add as $img ) {
			$queue_entry              = new IAPoster_DB_Queue();
			$queue_entry->description = '';
			$queue_entry->src         = $img['src'];
			$queue_entry->post_id     = $post_id;
			$queue_entry->status      = 'waiting';
			$queue_entry->post_status = $post->post_status;
			$this->queue_service->insert( $queue_entry );
		}
		if ( count( $images_to_add ) > 0 ) {
			$cron = IAPoster_Cron::get_instance();
			$cron->schedule_pinning();
		}
	}

	function get_parsed_content() {
		if ( ! check_admin_referer( $this->nonce_name )|| ! isset( $_REQUEST['content'] ) ) return;

		$content        = $_REQUEST['content'];
		$actual_content = apply_filters( 'the_content', stripslashes( $content ) );
		wp_send_json_success( $actual_content );
	}

	function get_status() {
		if ( ! check_admin_referer( $this->nonce_name ) || ! isset( $_REQUEST['post_id'] ) ) return;

		$post_id    = intval( $_REQUEST['post_id'] );
		$service    = new IAPoster_DB_QueueService();
		$pin_status = $service->get_for_post( $post_id );
		wp_send_json_success( $pin_status );
	}

	function remove_image() {
		if ( !check_admin_referer( $this->nonce_name ) || ! isset( $_REQUEST['image_id'] ) || ! isset( $_REQUEST['post_id'] ) ) {
			return;
		}
		$image_id = intval( $_REQUEST['image_id'] );
		$post_id  = intval( $_REQUEST['post_id'] );
		$service  = new IAPoster_DB_QueueService();
		$service->delete( $image_id );
		$pin_status = $service->get_for_post( $post_id );
		wp_send_json_success( $pin_status );
	}
}