<?php

class IAPoster_DB_Log {
	public $id;

	/**
	 * @var DateTime
	 */
	public $date;

	/**
	 * @var int
	 */
	public $type;

	/**
	 * @var string
	 */
	public $action;

	/**
	 * @var string
	 */
	public $text;

	public static function create_info( $action, $text = '', $date = null ) {
		return self::create( 0, $action, $text, $date );
	}

	public static function create_warning( $action, $text = '', $date = null ) {
		return self::create( 1, $action, $text, $date );
	}

	public static function create_error( $action, $text = '', $date = null ) {
		return self::create( 2, $action, $text, $date );
	}

	private static function create( $type, $action, $text, $date = null ) {
		$log         = new IAPoster_DB_Log();
		$log->type   = $type;
		$log->action = $action;
		$log->text   = $text;
		$log->date   = null == $date ? current_time( 'mysql' ) : $date;

		return $log;
	}

	public function is_error() {
		return 2 === $this->type;
	}

	public function get_db_data() {
		$result = array(
			'date'   => $this->date,
			'action' => $this->action,
			'text'   => $this->text,
			'type'   => $this->type,
		);

		return $result;
	}
}