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
    private $table;
    private $database = false;

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

    public function verify($user, $password)
    {
        if ($this->database) {
            return $this->verifyWithDatabase($user, $password);
        }
    }

    private function verifyWithDatabase($user, $password)
    {
        $sql = "select $this->fieldUser, $this->fieldPassword from $this->table where $this->fieldUser = :user";
        $row_user_stmt = $this->database->prepare($sql);
        $row_user_stmt->bindParam(':user', $user, \PDO::PARAM_STR);
        $row_user_stmt->execute();
        $row_user = $row_user_stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (count($row_user) == 1) {
            if (password_verify($password, $row_user[0][$this->fieldPassword])) {
                return true;
            }

            throw new \Exception("Password is wrong");
        }

        throw new \Exception("User is wrong");
    }
}
