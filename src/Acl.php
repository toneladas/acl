<?php

namespace Toneladas;

/**
 * Acl Library to handler access to systems
 * @package Acl
 * @version 1.0
 * @copyright 2017 Vanildo Souto Mangueira
 * @author Vanildo Souto Mangueira <vanildo.souto@gmail.com>
 * @license MIT
 */
class Acl
{
    private $fieldUser;
    private $fieldPassword;
    private $database;
    private $table;

    /**
     * Set field in database that related to password
     *
     * @param string $fieldPassword
     * @access public
     *
     * @return string
     */
    public function setFieldPassword($fieldPassword)
    {
        return $this->fieldPassword = $fieldPassword;
    }

    /**
     * Get field in database that related to password
     *
     * @access public
     *
     * @return string
     */
    public function getFieldPassword()
    {
        return $this->fieldPassword;
    }

    /**
     * Set field in database that related to user
     *
     * @param string $fieldUser
     * @access public
     *
     * @return string
     */
    public function setFieldUser($fieldUser)
    {
        return $this->fieldUser = $fieldUser;
    }

    /**
     * Get field in database that related to user
     *
     * @access public
     *
     * @return string
     */
    public function getFieldUser()
    {
        return $this->fieldUser;
    }

    /**
     * Set table name where is users
     * @param string $table
     * @access public
     *
     * @return string
     */
    public function setTable($table)
    {
        return $this->table = $table;
    }

    /**
     * Get table name where is users
     * @access public
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Set object PDO to connection to database
     * @param PDO $database
     * @access public
     *
     * @return PDO
     */
    public function setDatabase(\PDO $database)
    {
        return $this->database = $database;
    }

    /**
     * Get object PDO to connection to database
     * @access public
     *
     * @return PDO
     */
    public function getDatabase()
    {
        return $this->database;
    }
}
