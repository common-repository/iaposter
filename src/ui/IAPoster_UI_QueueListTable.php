<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class IAPoster_UI_QueueListTable extends WP_List_Table {

	/**
	 * @var IAPoster_DB_QueueService
	 */
	private $service;
	private $delete_nonce_action;
	private $posts_per_page = 20;
	private $pin_status_query_arg;

	function __construct( $slug, $args = array() ) {
		parent::__construct( $args );
		$this->delete_nonce_action  = $slug . '_delete_queue_item_';
		$this->pin_status_query_arg = $slug . '_pin_status';
		$this->service              = new IAPoster_DB_QueueService();
	}

	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$data     = $this->get_data();
		$count    = $this->get_count();
		$this->set_pagination_args( array(
			'total_items' => $count,
			'per_page'    => $this->posts_per_page
		) );
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $data;
	}

	public function column_cb( $item ) {
		return 'waiting' == $item['status']
			? sprintf( '<input type="checkbox" name="items[]" value="%s" />', $item['id'] )
			: '';
	}

	public function css() {
		?>
        <style type="text/css">
            .wp-list-table .column-status {
                width: 80px
            }
        </style>
		<?php
	}

	public function get_bulk_actions() {

		return array(
			'delete' => __( 'Delete', 'iaposter' ),
		);
	}

	/**
	 * @return Array
	 */
	public function get_columns() {
		$columns = array(
			'cb'          => '<input type="checkbox" />',
			'id'          => __( 'ID', 'iaposter' ),
			'post_id'     => __( 'Post', 'iaposter' ),
			'src'         => __( 'Image', 'iaposter' ),
			'post_status' => __( 'Post status', 'iaposter' ),
			'status'      => __( 'Status', 'iaposter' ),
			'description' => __( 'Description', 'iaposter' ),
			'pin_url'     => __( 'Pin URL', 'iaposter' ),
			'pin_date'    => __( 'Pin date', 'iaposter' )
		);

		return $columns;
	}

	public function notifications() {
		if ( ! isset( $_REQUEST['deleted'] ) ) {
			return;
		}
		?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'Entry deleted from queue!', 'iaposter' ); ?></p>
        </div>
		<?php
	}

	/**
	 * @return Array
	 */
	public function get_hidden_columns() {
		return array( 'description' );
	}

	/**
	 * @return Array
	 */
	public function get_sortable_columns() {
		return array(
			'status' => array( 'status', false ),
			'id'     => array( 'id', false )
		);
	}

	private function get_view_url( $arg_value, $arg_name, $current ) {
		$url   = add_query_arg( $this->pin_status_query_arg, $arg_value );
		$url   = remove_query_arg( 'paged', $url );
		$class = $current === $arg_value ? 'class="current"' : '';

		return sprintf( '<a href="%s" %s>%s</a>', $url, $class, $arg_name );
	}

	protected function get_views() {
		$current = ( ! empty( $_REQUEST[ $this->pin_status_query_arg ] ) ? $_REQUEST[ $this->pin_status_query_arg ] : 'all' );

		$status_links = array(
			"all"     => $this->get_view_url( 'all', __( 'All', 'iaposter' ), $current ),
			"waiting" => $this->get_view_url( 'waiting', __( 'Waiting', 'iaposter' ), $current ),
			"done"    => $this->get_view_url( 'done', __( 'Pinned', 'iaposter' ), $current )
		);

		return $status_links;
	}

	private function get_count() {
		$filter = $this->get_current_filter();

		return $this->service->get_count( $filter );
	}

	private function get_data() {
		$orderby  = ! empty( $_GET['orderby'] ) ? $_GET['orderby'] : 'id';
		$order    = ! empty( $_GET['order'] ) ? $_GET['order'] : 'desc';
		$filter   = $this->get_current_filter();
		$page_num = $this->get_pagenum();

		return $this->service->get_all(
			$filter,
			array( 'by' => $orderby, 'dir' => $order ),
			array( 'page_num' => $page_num, 'per_page' => $this->posts_per_page )
		);
	}

	private function get_filter( $log_type ) {
		switch ( $log_type ) {
			case 'done':
			case 'waiting':
				return array( 'status' => $log_type );
			case 'all':
			default:
				return array();
		}
	}

	private function get_current_filter() {
		return ! empty( $_REQUEST[ $this->pin_status_query_arg ] )
			? $this->get_filter( $_REQUEST[ $this->pin_status_query_arg ] )
			: array();
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param  Array $item Data
	 * @param  String $column_name - Current column name
	 *
	 * @return Mixed
	 */
	public function column_default( $item, $column_name ) {
		$value = isset( $item[ $column_name ] ) ? $item[ $column_name ] : null;
		switch ( $column_name ) {
			case 'id':
				$actions = 'waiting' === $item['status']
					? $this->row_actions( array(
							'delete' => sprintf( '<a href="%s">Delete</a>', add_query_arg( array(
								'action'   => 'delete',
								'id'       => $value,
								'_wpnonce' => wp_create_nonce( $this->delete_nonce_action . $value )
							) ) )
						)
					)
					: '';

				return sprintf( '%1$s %2$s', $value, $actions );
			case 'post_id':
				return sprintf( '<a href="%s" target="_blank">%s</a>', get_permalink( $value ), get_the_title( $value ) );
			case 'src':
				return sprintf( '<img src="%s" style="max-width: 100px; max-height: 100px;" />', $value );
			case 'status':
				switch ( $value ) {
					case 'waiting':
						return '<span class="dashicons dashicons-clock"></span>';
					case 'done':
						return '<span class="dashicons dashicons-yes"></span>';
					default:
						return $value;
				}
			case 'post_status':
				switch ( $value ) {
					case 'publish':
						return __( 'Published', 'iaposter' );
					default:
						return $value;
				}
			case 'pin_url':
				return '' != $value
					? sprintf( '<a href="%s" target="_blank">Pin</a>', $value )
					: '';
			case 'id':
			default:
				return $value;
		}
	}

	function process_actions() {
		$action = $this->current_action();
		$this->process_single( $action );
		$this->process_bulk( $action );
	}

	private function process_single( $action ) {
		if ( ! $action || ! isset( $_REQUEST['id'] ) || ! check_ajax_referer( $this->delete_nonce_action . $_REQUEST['id'] ) ) {
			return;
		}
		$id = $_REQUEST['id'];

		switch ( $action ) {
			case 'delete':
				$res      = $this->service->delete( $id );
				$redirect = remove_query_arg( array( 'action', 'id', '_wpnonce' ) );
				$redirect = add_query_arg( 'deleted', 1, $redirect );
				wp_redirect( $redirect );
				exit;
		}
	}

	private function process_bulk( $action ) {
		if ( ! $action || ! isset( $_POST['items'] ) ) {
			return;
		}
		$items = $_POST['items'];
		switch ( $action ) {
			case 'delete':
				$count    = $this->service->delete_bulk( $items );
				$redirect = remove_query_arg( array( 'action', 'id', '_wpnonce', 'paged' ) );
				$redirect = add_query_arg( 'deleted', $count, $redirect );
				wp_redirect( $redirect );
				exit;
		}
	}
}