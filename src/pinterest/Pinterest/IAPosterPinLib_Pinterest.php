<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @property IAPosterPinLib_Endpoints_Boards boards
 * @property IAPosterPinLib_Endpoints_Following following
 * @property IAPosterPinLib_Endpoints_Pins pins
 * @property IAPosterPinLib_Endpoints_Users users
 */
class IAPosterPinLib_Pinterest {

    /**
     * Reference to authentication class instance
     *
     * @var IAPosterPinLib_Auth_PinterestOAuth
     */
    public $auth;

    /**
     * A reference to the request class which travels
     * through the application
     *
     * @var IAPosterPinLib_Transport_Request
     */
    public $request;

    /**
     * A array containing the cached endpoints
     *
     * @var array
     */
    private $cachedEndpoints = array();

    /**
     * Constructor
     *
     * @param  string       $client_id
     * @param  string       $client_secret
     * @param  IAPosterPinLib_Utils_CurlBuilder  $curlbuilder
     */
    public function __construct($client_id, $client_secret, $curlbuilder = null)
    {
        if ($curlbuilder == null) {
            $curlbuilder = new IAPosterPinLib_Utils_CurlBuilder();
        }

        $this->request = new IAPosterPinLib_Transport_Request($curlbuilder);

        // Create and set new instance of the OAuth class
        $this->auth = new IAPosterPinLib_Auth_PinterestOAuth($client_id, $client_secret, $this->request);
    }

    /**
     * Get an Pinterest API endpoint
     *
     * @access public
     * @param string    $endpoint
     * @return mixed
     * @throws IAPosterPinLib_Exceptions_InvalidEndpointException
     */
    public function __get($endpoint)
    {
        $endpoint = strtolower($endpoint);
        $class = "IAPosterPinLib_Endpoints_" . ucfirst($endpoint);

        // Check if an instance has already been initiated
        if (!isset($this->cachedEndpoints[$endpoint])) {
            // Check endpoint existence
            if (!class_exists($class)) {
                throw new IAPosterPinLib_Exceptions_InvalidEndpointException;
            }

            // Create a reflection of the called class and initialize it
            // with a reference to the request class
            $ref = new ReflectionClass($class);
            $obj = $ref->newInstanceArgs( array( $this->request, $this ));

            $this->cachedEndpoints[$endpoint] = $obj;
        }

        return $this->cachedEndpoints[$endpoint];
    }

    /**
     * Get rate limit from the headers
     *
     * @access public
     * @return integer
     */
    public function getRateLimit()
    {
        $header = $this->request->getHeaders();
        return (isset($header['X-Ratelimit-Limit']) ? $header['X-Ratelimit-Limit'] : 1000);
    }

    /**
     * Get rate limit remaining from the headers
     *
     * @access public
     * @return mixed
     */
    public function getRateLimitRemaining()
    {
        $header = $this->request->getHeaders();
        return (isset($header['X-Ratelimit-Remaining']) ? $header['X-Ratelimit-Remaining'] : 'unknown');
    }
}
