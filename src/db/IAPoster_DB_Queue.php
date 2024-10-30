<?php

class IAPoster_DB_Queue {
	/**
	 * @var int
	 */
	public $id;
	/**
	 * @var int
	 */
	public $post_id;

	/**
	 * @var string
	 */
	public $board_id = '0';
	/**
	 * @var string
	 */
	public $post_status;
	/**
	 * @var string
	 */
	public $status;
	/**
	 * @var string
	 */
	public $src;
	/**
	 * @var string
	 */
	public $description;
	/**
	 * @var string
	 */
	public $pin_url;
	/**
	 * @var DateTime
	 */
	public $pin_date;

	/**
	 * @var string
	 */
	public $pin_id;

	public function get_db_data() {
		$result = array(
			'post_id'     => $this->post_id,
			'post_status' => $this->post_status,
			'board_id'    => $this->board_id,
			'status'      => $this->status,
			'src'         => $this->src,
			'description' => $this->description,
		);
		if ( isset( $this->pin_url ) ) {
			$result['pin_url'] = $this->pin_url;
		}
		if ( isset( $this->pin_date ) ) {
			$result['pin_date'] = $this->pin_date;
		}

		if ( isset( $this->pin_id ) ) {
			$result['pin_id'] = $this->pin_id;
		}

		return $result;
	}

	public static function from_array( $db_entry ) {
		$result              = new IAPoster_DB_Queue();
		$result->id          = $db_entry['id'];
		$result->post_id     = $db_entry['post_id'];
		$result->post_status = $db_entry['post_status'];
		$result->board_id    = $db_entry['board_id'];
		$result->status      = $db_entry['status'];
		$result->src         = $db_entry['src'];
		$result->description = $db_entry['description'];

		if ( isset( $db_entry['pin_url'] ) ) {
			$result->pin_url = $db_entry['pin_url'];
		}
		if ( isset( $db_entry['pin_date'] ) ) {
			$result->pin_date = $db_entry['pin_date'];
		}
		if ( isset( $db_entry['pin_id'] ) ) {
			$result->pin_id = $db_entry['pin_id'];
		}

		return $result;
	}
}