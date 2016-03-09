<?php

/**
 * The Number Model
 *
 * @author Vijayant Saini
 */
class Number extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 12
     * 
     * @validate required, alphanumeric, min(11), max(12)
     * @label mobile number in international format (12 digits)
     */
    protected $_number;


    /**
     * @column
     * @readwrite
     * @type text
     * @validate required
     * @label user id to associate contact with
     */
    protected $_uid;

}
