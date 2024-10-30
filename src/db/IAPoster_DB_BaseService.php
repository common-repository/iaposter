<?php

abstract class IAPoster_DB_BaseService {

	protected $table_name;

	public function __construct( $table_name ) {
		global $wpdb;
		$this->table_name = $wpdb->prefix . $table_name;
	}

	/**
	 * @param $ids int[]
	 *
	 * @return false|int
	 */
	function delete_bulk( $ids ) {
		global $wpdb;
		$ids_str = implode( ',', array_map( 'absint', $ids ) );

		return $wpdb->query( "DELETE FROM $this->table_name WHERE id IN($ids_str)" );
	}

	/**
	 * @param array $filter
	 * @param array $order
	 * @param array $paging
	 *
	 * @return array
	 */
	function get_all(
		$filter = array(),
		$order = array( 'by' => 'id', 'dir' => 'desc' ),
		$paging = array( 'per_page' => 20, 'page_num' => 1 )
	) {
		global $wpdb;
		$order_by    = $order['by'];
		$order_dir   = $order['dir'];
		$per_page    = $paging['per_page'];
		$page_num    = $paging['page_num'];
		$offset      = ( $page_num - 1 ) * $per_page;
		$filter_text = $this->filter_text( $filter );

		return $wpdb->get_results( "SELECT * FROM $this->table_name $filter_text ORDER BY $order_by $order_dir LIMIT $per_page OFFSET $offset", ARRAY_A );
	}

	function get_count( $filter = array() ) {
		global $wpdb;
		$filter_text = $this->filter_text( $filter );

		$count = $wpdb->get_var( "SELECT COUNT(*) FROM $this->table_name $filter_text" );
		return null == $count ? 0 : intval( $count );
	}

	/**
	 * @param $filter array
	 */
	private function filter_text( $filter ) {
		$filter_text = 'WHERE 1 = 1';
		foreach ( $filter as $key => $value ) {
			$filter_text .= sprintf( ' AND `%s` = "%s"', $key, $value );
		}

		return $filter_text;
	}
}