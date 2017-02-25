<?php

namespace tests;

class AclTest extends \PHPUnit_Framework_TestCase
{
    private $conn;
    private $doctrine;
    private $acl;

    public function setUp()
    {
        $this->conn = new \PDO('sqlite::memory:');
        $create = 'create table users (id_user int primary key, user varchar(20), password varchar(150))';
        $insert = 'insert into users (id_user, user, password) values (1, "admin", "1234")';
        $this->conn->exec($create);
        $this->conn->exec($insert);

        $this->acl = new \Toneladas\Acl();
    }

    public function testConfigurationWithDatabase()
    {
        $this->acl->setDatabase($this->conn);
        $this->acl->setTable('users');
        $this->acl->setFieldUser('user');
        $this->acl->setFieldPassword('password');

        $this->assertEquals('users', $this->acl->getTable());
        $this->assertEquals('user', $this->acl->getFieldUser());
        $this->assertEquals('password', $this->acl->getFieldPassword());
    }

    /**
     * @depends testConfigurationWithDatabase
     */
    public function testVerifyUserWithDatabase()
    {
    }
}