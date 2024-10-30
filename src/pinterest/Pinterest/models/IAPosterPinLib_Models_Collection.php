<?php

/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class IAPosterPinLib_Models_Collection implements JsonSerializable, ArrayAccess, IteratorAggregate {

	/**
	 * The items in the collection
	 *
	 * @var array
	 */
	private $items = array();

	/**
	 * The model of each collection item
	 *
	 * @var string
	 */
	private $model;

	/**
	 * Stores the pagination object
	 *
	 * @var array|boolean
	 */
	public $pagination;

	/**
	 * Instance of Pinterest master class
	 *
	 * @var IAPosterPinLib_Pinterest
	 */
	private $master;

	/**
	 * Response instance
	 *
	 * @var IAPosterPinLib_Transport_Response
	 */
	private $response;

	/**
	 * Construct
	 *
	 * @access public
	 *
	 * @param  IAPosterPinLib_Pinterest $master
	 * @param  array|IAPosterPinLib_Transport_Response $items
	 * @param  string $model
	 *
	 * @throws IAPosterPinLib_Exceptions_InvalidModelException
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 */
	public function __construct( $master, $items, $model ) {
		$this->master = $master;

		// Create class path
		$this->model = ucfirst( strtolower( $model ) );

		if ( ! class_exists( "IAPosterPinLib_Models_" . $this->model ) ) {
			throw new IAPosterPinLib_Exceptions_InvalidModelException;
		}

		// Get items and response instance
		if ( is_array( $items ) ) {
			$this->response = null;
			$this->items    = $items;
		} else if ( $items instanceof IAPosterPinLib_Transport_Response ) {
			$this->response = $items;
			$this->items    = $items->data;
		} else {
			throw new IAPosterPinLib_Exceptions_PinterestException( "$items needs to be an instance of Response or an array." );
		}

		// Transform the raw collection data to models
		$this->items = $this->buildCollectionModels( $this->items );

		// Add pagination object
		if ( isset( $this->response->page ) && ! empty( $this->response->page['next'] ) ) {
			$this->pagination = $this->response->page;
		} else {
			$this->pagination = false;
		}
	}

	/**
	 * Get all items from the collection
	 *
	 * @access public
	 * @return array
	 */
	public function all() {
		return $this->items;
	}

	/**
	 * Transform each raw item into a model
	 *
	 * @access private
	 *
	 * @param array $items
	 *
	 * @return array
	 */
	private function buildCollectionModels( array $items ) {
		$modelcollection = array();

		foreach ( $items as $item ) {
			$class             = new ReflectionClass( "IAPosterPinLib_Models_" . $this->model );
			$modelcollection[] = $class->newInstanceArgs( array( $this->master, $item ) );
		}

		return $modelcollection;
	}

	/**
	 * Check if their is a next page available
	 *
	 * @access public
	 * @return boolean
	 */
	public function hasNextPage() {
		return ( $this->response != null && isset( $this->response->page['next'] ) );
	}

	/**
	 * Return the item at the given index
	 *
	 * @access public
	 *
	 * @param  int $index
	 *
	 * @return IAPosterPinLib_Models_Model
	 */
	public function get( $index ) {
		return $this->items[ $index ];
	}

	/**
	 * Convert the collection to an array
	 *
	 * @access public
	 * @return array
	 */
	public function toArray() {
		$items = array();

		foreach ( $this->items as $item ) {
			$items[] = $item->toArray();
		}

		return array(
			"data" => $items,
			"page" => $this->pagination
		);
	}

	/**
	 * Convert the collection to JSON
	 *
	 * @access public
	 * @return string
	 */
	public function toJson() {
		return json_encode( $this->toArray() );
	}

	/**
	 * Convert the object into something JSON serializable.
	 *
	 * @access public
	 * @return array
	 */
	public function jsonSerialize() {
		return $this->toArray();
	}

	/**
	 * Convert the collection to its string representation
	 *
	 * @access public
	 * @return string
	 */
	public function __toString() {
		return $this->toJson();
	}

	/**
	 * Determine if the given item exists.
	 *
	 * @access public
	 *
	 * @param  mixed $offset
	 *
	 * @return bool
	 */
	public function offsetExists( $offset ) {
		return isset( $this->items[ $offset ] );
	}

	/**
	 * Get the value for a given offset.
	 *
	 * @access public
	 *
	 * @param  mixed $offset
	 *
	 * @return mixed
	 */
	public function offsetGet( $offset ) {
		return $this->items[ $offset ];
	}

	/**
	 * Set the value for a given offset.
	 *
	 * @access public
	 *
	 * @param  mixed $offset
	 * @param  mixed $value
	 *
	 * @return void
	 */
	public function offsetSet( $offset, $value ) {
		$this->items[ $offset ] = $value;
	}

	/**
	 * Unset the value for a given offset.
	 *
	 * @access public
	 *
	 * @param  mixed $offset
	 *
	 * @return void
	 */
	public function offsetUnset( $offset ) {
		unset( $this->items[ $offset ] );
	}

	/**
	 * Make the collection items iteratable
	 *
	 * @access public
	 * @return ArrayIterator
	 */
	public function getIterator() {
		return new ArrayIterator( $this->items );
	}

}