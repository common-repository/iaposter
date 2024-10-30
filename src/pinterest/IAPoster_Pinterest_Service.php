<?php

class IAPoster_Pinterest_Service {
	const app_id = '4903826883906387217';
	const app_secret = 'f1c64cab5e9d067462ea5a2fbced7c565ab6cc1ed9ff15bbf0ed57108128f52e';
	const callback_url = 'https://highfiveplugins.com/oauth_redirect.html';

	function getLoginUrl( $callback_url ) {
		$api = $this->createAPIInstance();
		$api->auth->setState( $callback_url );

		return $api->auth->getLoginUrl( self::callback_url, array(
			'read_public',
			'write_public'
		) );
	}

	function getMeBoards( $token ) {
		if ( ! $token ) {
			return array();
		}
		$api = $this->createAPIInstance();
		$api->auth->setOAuthToken( $token );
		try {
			$boards       = $api->users->getMeBoards( array( 'fields' => 'id,name' ) );
			$boards_array = iterator_to_array( $boards );
			$result       = array();
			for ( $i = 0; $i < count( $boards_array ); $i ++ ) {
				$result[ strval( $boards_array[ $i ]->id )] = $boards_array[ $i ]->name;
			}

			return $result;
		} catch ( Exception $ex ) {
			return array();
		}
	}

	function getOAuthToken( $code ) {
		$api      = $this->createAPIInstance();
		$response = $api->auth->getOAuthToken( $code );

		return $response->access_token;
	}

	function pinImage( $token, $src, $description, $url, $board_id ) {
		$api = $this->createAPIInstance();
		$api->auth->setOAuthToken( $token );
		$data = array(
			'note'      => $description,
			'image_url' => $src,
			'link'      => $url,
			'board' =>     $board_id,
		);
		$pin  = $api->pins->create( $data );

		return array(
			'id'  => $pin->id,
			'url' => $pin->url
		);
	}

	private function createAPIInstance() {
		return new IAPosterPinLib_Pinterest( self::app_id, self::app_secret );
	}

}