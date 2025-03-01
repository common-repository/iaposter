<?php

/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class IAPosterPinLib_Models_Model implements JsonSerializable {

	/**
	 * The model's attributes
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * The available object keys
	 *
	 * @var array
	 */
	protected $fillable = array();

	/**
	 * Instance of the master Pinterest class
	 *
	 * @var IAPosterPinLib_Pinterest
	 */
	protected $master;

	/**
	 * Create a new model instance
	 *
	 * @param  IAPosterPinLib_Pinterest $master
	 * @param  mixed $modeldata
	 */
	public function __construct( $master, $modeldata = null ) {
		$this->master = $master;

		// Fill the model
		if ( is_array( $modeldata ) ) {
			$this->fill( $modeldata );
		} else if ( $modeldata instanceof IAPosterPinLib_Transport_Response ) {
			$this->fill( $modeldata->data );
		}
	}

	/**
	 * Get the model's attribute
	 *
	 * @access public
	 *
	 * @param  string $key
	 *
	 * @return mixed
	 */
	public function __get( $key ) {
		return isset( $this->attributes[ $key ] ) ? $this->attributes[ $key ] : null;
	}

	/**
	 * Set the model's attribute
	 *
	 * @access public
	 *
	 * @param  string $key
	 * @param  mixed $value
	 *
	 * @throws IAPosterPinLib_Exceptions_PinterestException
	 * @return void
	 */
	public function __set( $key, $value ) {
		if ( $this->isFillable( $key ) ) {
			$this->attributes[ $key ] = $value;
		} else {
			throw new IAPosterPinLib_Exceptions_PinterestException( sprintf( "%s is not a fillable attribute.", $key ) );
		}
	}

	/**
	 * Check if the model's attribute is set
	 *
	 * @param $key
	 *
	 * @return bool
	 */
	public function __isset( $key ) {
		return array_key_exists( $key, $this->attributes );
	}

	/**
	 * Fill the attributes
	 *
	 * @access private
	 *
	 * @param  array $attributes
	 *
	 * @return void
	 */
	private function fill( array $attributes ) {
		foreach ( $attributes as $key => $value ) {
			if ( $this->isFillable( $key ) ) {
				$this->attributes[ $key ] = $value;
			}
		}
	}

	/**
	 * Check if the key is fillable
	 *
	 * @access public
	 *
	 * @param  string $key
	 *
	 * @return boolean
	 */
	public function isFillable( $key ) {
		return in_array( $key, $this->fillable );
	}

	/**
	 * Convert the model instance to an array
	 *
	 * @access public
	 * @return array
	 */
	public function toArray() {
		$array = array();

		foreach ( $this->fillable as $key ) {
			$array[ $key ] = $this->{$key};
		}

		return $array;
	}

	/**
	 * Convert the model instance to JSON
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
	 * Convert the model to its string representation
	 *
	 * @access public
	 * @return string
	 */
	public function __toString() {
		return $this->toJson();
	}
}