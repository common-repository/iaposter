<?php

class IAPoster_DB_QueueService extends IAPoster_DB_BaseService {

	public function __construct() {
		parent::__construct('iaposter_queue');
	}

	public function setup_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $this->table_name (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  post_id bigint(14) NOT NULL,
  board_id tinytext NOT NULL,
  src tinytext NOT NULL,
  post_status tinytext NOT NULL,
  status tinytext NOT NULL,
  description tinytext NULL,
  pin_id tinytext NULL,
  pin_url tinytext NULL,
  pin_date datetime NULL,
  PRIMARY KEY  (id),
  KEY post_id (post_id)

) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * @param $queue IAPoster_DB_Queue
	 */
	public function insert( $queue ) {
		global $wpdb;

		$wpdb->insert(
			$this->table_name,
			$queue->get_db_data()
		);
	}

	public function delete( $id ) {
		global $wpdb;

		return $wpdb->delete( $this->table_name, array( 'id' => $id, 'status' => 'waiting' ) );
	}

	public function delete_by_post_id( $post_id ) {
		global $wpdb;

		return $wpdb->delete( $this->table_name, array( 'post_id' => $post_id ) );
	}

	/**
	 * @param $post_id int
	 *
	 * @return IAPoster_DB_Queue[]
	 */
	public function get_for_post( $post_id ) {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare("SELECT * FROM  $this->table_name WHERE post_id = %d", $post_id ) );
	}

	/**
	 * @return null|IAPoster_DB_Queue
	 */
	public function get_next_entry_to_process() {
		global $wpdb;

		$row = $wpdb->get_row( "SELECT * FROM $this->table_name WHERE post_status = 'publish' and status = 'waiting' ORDER BY id LIMIT 1", ARRAY_A );

		return null !== $row ? IAPoster_DB_Queue::from_array( $row ) : null;
	}

	/**
	 * When a post status changes, we need to update it in the database and schedule pinning in case there's something new to pin.
	 *
	 * @param $post_id
	 * @param string $status
	 *
	 * @return false|int
	 */
	public function update_post_status( $post_id, $status = 'publish' ) {
		global $wpdb;

		return $wpdb->update( $this->table_name, array( 'post_status' => $status ), array( 'post_id' => $post_id ) );
	}

	public function mark_as_pinned( $queue_entry_id, $pin_id, $pin_url ) {
		global $wpdb;

		return $wpdb->update(
			$this->table_name,
			array( 'pin_id'   => $pin_id,
			       'pin_url'  => $pin_url,
			       'pin_date' => current_time( 'mysql' ),
			       'status'   => 'done'
			),
			array( 'id' => $queue_entry_id )
		);
	}
}