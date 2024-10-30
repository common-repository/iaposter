<?php

/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class IAPosterPinLib_Endpoints_Users extends IAPosterPinLib_Endpoints_Endpoint {

	/**
	 * Get the current user
	 *
	 * @access public
	 *
	 * @param array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_User
	 */
	public function me( array $data = array() ) {
		$response = $this->request->get( "me", $data );

		return new IAPosterPinLib_Models_User( $this->master, $response );
	}

	/**
	 * Get the provided user
	 *
	 * @access public
	 *
	 * @param string $username
	 * @param array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_User
	 */
	public function find( $username, array $data = array() ) {
		$response = $this->request->get( sprintf( "users/%s", $username ), $data );

		return new IAPosterPinLib_Models_User( $this->master, $response );
	}

	/**
	 * Get the authenticated user's pins
	 *
	 * @access public
	 *
	 * @param array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Collection
	 */
	public function getMePins( array $data = array() ) {
		$response = $this->request->get( "me/pins", $data );

		return new IAPosterPinLib_Models_Collection( $this->master, $response, "Pin" );
	}

	/**
	 * Search in the user's pins
	 *
	 * @param  string $query
	 * @param  array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Collection
	 */
	public function searchMePins( $query, array $data = array() ) {
		$data["query"] = $query;
		$response      = $this->request->get( "me/search/pins", $data );

		return new IAPosterPinLib_Models_Collection( $this->master, $response, "Pin" );
	}

	/**
	 * Search in the user's boards
	 *
	 * @param  string $query
	 * @param  array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Collection
	 */
	public function searchMeBoards( $query, array $data = array() ) {
		$data["query"] = $query;

		$response = $this->request->get( "me/search/boards", $data );

		return new IAPosterPinLib_Models_Collection( $this->master, $response, "Board" );
	}

	/**
	 * Get the authenticated user's boards
	 *
	 * @access public
	 *
	 * @param array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Collection
	 */
	public function getMeBoards( array $data = array() ) {
		$response = $this->request->get( "me/boards", $data );

		return new IAPosterPinLib_Models_Collection( $this->master, $response, "Board" );
	}

	/**
	 * Get the authenticated user's likes
	 *
	 * @access public
	 *
	 * @param array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Collection
	 */
	public function getMeLikes( array $data = array() ) {
		$response = $this->request->get( "me/likes", $data );

		return new IAPosterPinLib_Models_Collection( $this->master, $response, "Pin" );
	}

	/**
	 * Get the authenticated user's followers
	 *
	 * @access public
	 *
	 * @param array $data
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return IAPosterPinLib_Models_Collection
	 */
	public function getMeFollowers( array $data = array() ) {
		$response = $this->request->get( "me/followers", $data );

		return new IAPosterPinLib_Models_Collection( $this->master, $response, "User" );
	}

}
