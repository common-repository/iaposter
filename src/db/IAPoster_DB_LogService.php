<?php


class IAPoster_DB_LogService extends IAPoster_DB_BaseService {

	function __construct() {
		parent::__construct( 'iaposter_logs' );
	}

	function setup_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $this->table_name (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  date datetime NOT NULL,
  type tinyint(1) NOT NULL,
  action tinytext NOT NULL,
  text tinytext NOT NULL,
  PRIMARY KEY  (id),
  KEY type (type)

) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	/**
	 * @param $entry IAPoster_DB_Log
	 */
	function insert( $entry ) {
		global $wpdb;

		$wpdb->insert(
			$this->table_name,
			$entry->get_db_data()
		);
	}

	function delete( $id ) {
		global $wpdb;

		return $wpdb->delete( $this->table_name, array( 'id' => $id ) );
	}

	/**
	 * @param $days
	 *
	 * @return int
	 */
	function delete_older_than_x_days( $days = 14 ) {
		global $wpdb;

		$older_than_timestamp = time() - DAY_IN_SECONDS * $days;
		$older_than_val       = gmdate( 'Y-m-d H:i:s', $older_than_timestamp );

		return $wpdb->query( $wpdb->prepare( "DELETE FROM $this->table_name WHERE `date` < %s", $older_than_val ) );
	}
}