<?php

/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class IAPosterPinLib_Endpoints_Pins extends IAPosterPinLib_Endpoints_Endpoint {

	/**
	 * Get a pin object
	 *
	 * @access public
	 *
	 * @param  string $pin_id
	 * @param array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Pin
	 */
	public function get( $pin_id, array $data = array() ) {
		$response = $this->request->get( sprintf( "pins/%s", $pin_id ), $data );

		return new IAPosterPinLib_Models_Pin( $this->master, $response );
	}

	/**
	 * Get all pins from the given board
	 *
	 * @access public
	 *
	 * @param  string $board_id
	 * @param array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Collection
	 */
	public function fromBoard( $board_id, array $data = array() ) {
		$response = $this->request->get( sprintf( "boards/%s/pins", $board_id ), $data );

		return new IAPosterPinLib_Models_Collection( $this->master, $response, "Pin" );
	}

	/**
	 * Create a pin
	 *
	 * @access public
	 *
	 * @param  array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Pin
	 */
	public function create( array $data ) {
		if ( array_key_exists( "image", $data ) ) {
			if ( class_exists( '\CURLFile' ) ) {
				$data["image"] = new CURLFile( $data['image'] );
			} else {
				$data["image"] = '@' . $data['image'];
			}
		}

		$response = $this->request->post( "pins", $data );

		return new IAPosterPinLib_Models_Pin( $this->master, $response );
	}

	/**
	 * Edit a pin
	 *
	 * @access public
	 *
	 * @param  string $pin_id
	 * @param  array $data
	 * @param  string $fields
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Pin
	 */
	public function edit( $pin_id, array $data, $fields = null ) {
		$query = ( ! $fields ) ? array() : array( "fields" => $fields );

		$response = $this->request->update( sprintf( "pins/%s/", $pin_id ), $data, $query );

		return new IAPosterPinLib_Models_Pin( $this->master, $response );
	}

	/**
	 * Delete a pin
	 *
	 * @access public
	 *
	 * @param  string $pin_id
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return boolean
	 */
	public function delete( $pin_id ) {
		$this->request->delete( sprintf( "pins/%s", $pin_id ) );

		return true;
	}
}