<?php

/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class IAPosterPinLib_Endpoints_Endpoint {

	/**
	 * Instance of the request class
	 *
	 * @var IAPosterPinLib_Transport_Request
	 */
	protected $request;

	/**
	 * Instance of the master class
	 *
	 * @var IAPosterPinLib_Pinterest
	 */
	protected $master;

	/**
	 * Create a new model instance
	 *
	 * @param  IAPosterPinLib_Transport_Request $request
	 * @param  IAPosterPinLib_Pinterest $master
	 */
	public function __construct( $request, $master ) {
		$this->request = $request;
		$this->master  = $master;
	}

}