<?php
/**
 * Copyright 2015 Dirk Groenen
 *
 * (c) Dirk Groenen <dirk@bitlabs.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class IAPosterPinLib_Models_Board extends IAPosterPinLib_Models_Model {

    /**
     * The available object keys
     *
     * @var array
     */
    protected $fillable =  array( "id", "name", "url", "description", "creator", "created_at", "counts", "image" );

}