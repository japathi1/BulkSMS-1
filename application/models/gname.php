<?php

/**
 * The Group Model
 *
 * @author Vijayant Saini
 */
class Gname extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @length 100
     * 
     * @validate required, max(100)
     * @label name of the group
     */
    protected $_name;

    /**
     * @column
     * @readwrite
     * @type text
     * @validate required
     * @label user id to associate group with
     */
    protected $_uid;

}
