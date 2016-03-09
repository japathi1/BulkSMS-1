<?php

/**
 * The Group member Model
 *
 * @author Vijayant Saini
 */
class Gmember extends Shared\Model {

    /**
     * @column
     * @readwrite
     * @type text
     * @validate required
     * @label group id to associate group member with
     */
    protected $_gid;

    /**
     * @column
     * @readwrite
     * @type text
     * @validate required
     * @label user id to associate group member with
     */
    protected $_uid;

    /**
     * @column
     * @readwrite
     * @type text
     * @validate required
     * @label contact id to associate group member with
     */
    protected $_cid;

}
