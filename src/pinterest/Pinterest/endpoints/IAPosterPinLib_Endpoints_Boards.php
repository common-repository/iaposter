<?php

/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class IAPosterPinLib_Endpoints_Boards extends IAPosterPinLib_Endpoints_Endpoint {

	/**
	 * Find the provided board
	 *
	 * @access public
	 *
	 * @param  string $board_id
	 * @param  array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Board()
	 */
	public function get( $board_id, array $data = array() ) {
		$response = $this->request->get( sprintf( "boards/%s", $board_id ), $data );

		return new IAPosterPinLib_Models_Board( $this->master, $response );
	}

	/**
	 * Create a new board
	 *
	 * @access public
	 *
	 * @param  array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Board()
	 */
	public function create( array $data ) {
		$response = $this->request->post( "boards", $data );

		return new IAPosterPinLib_Models_Board( $this->master, $response );
	}

	/**
	 * Edit a board
	 *
	 * @access public
	 *
	 * @param  string $board_id
	 * @param  array $data
	 * @param  string $fields
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Board()
	 */
	public function edit( $board_id, array $data, $fields = null ) {
		$query = ( ! $fields ) ? array() : array( "fields" => $fields );

		$response = $this->request->update( sprintf( "boards/%s", $board_id ), $data, $query );

		return new IAPosterPinLib_Models_Board( $this->master, $response );
	}

	/**
	 * Delete a board
	 *
	 * @access public
	 *
	 * @param  string $board_id
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return boolean
	 */
	public function delete( $board_id ) {
		$this->request->delete( sprintf( "boards/%s", $board_id ) );

		return true;
	}
}