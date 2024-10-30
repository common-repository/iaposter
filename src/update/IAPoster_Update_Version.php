<?php

class IAPoster_Update_Version {
	private $db_version_option_name;
	private $version;

	function __construct( $version, $slug ) {
		$this->version = $version;
		$this->db_version_option_name = $slug . '_version';
	}

	function run_updates() {
		$db_version = $this->get_db_version();

		if ($db_version == $this->version) {
			return;
		}

		if ( false === $db_version ) {
			$this->setup();
		}

		if ( version_compare( $db_version, '1.1', 'lt') ) {
			$this->update_1_1();
		}
		$this->update_db_version();
	}

	private function get_db_version() {
		return get_option( $this->db_version_option_name, false);
	}

	private function update_db_version() {
		update_option( $this->db_version_option_name, $this->version );
	}

	/* UPDATES */
	private function setup() {
		$queue_service = new IAPoster_DB_QueueService();
		$queue_service->setup_table();

		$log_service = new IAPoster_DB_LogService();
		$log_service->setup_table();
	}

	private function update_1_1() {
	}
}