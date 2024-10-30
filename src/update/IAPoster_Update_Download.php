<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class IAPoster_Update_Download {
	static $store_url = 'https://highfiveplugins.com';
	static $item_name = 'Image Auto Poster';

	public function __construct( $file, $version ) {

		$options     = new IAPoster_Option_License();
		$option_val  = $options->get();
		$license_key = trim( $option_val['key'] );

		$edd_updater = new IAPoster_Update_EddUpdater( self::$store_url, $file, array(
				'version'   => $version,
				'license'   => $license_key,
				'item_name' => self::$item_name,
				'author'    => 'Marcin Skrzypiec',
				'url'       => home_url()
			)
		);
	}
}

