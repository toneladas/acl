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
        $password = '$2y$10$YNThSle0yX6YBFJmBTqt3.zKLcB5Rxcl0p.89wWO8zH4s.dhWkKeG';
        $insert = "insert into users (id_user, user, password) values (1, 'admin', '$password')";
        $insertEmail = "insert into users (id_user, user, password) values (2, 'admin@admin.com', '$password')";
        $this->conn->exec($create);
        $this->conn->exec($insert);
        $this->conn->exec($insertEmail);

        $this->acl = new \Toneladas\Acl();
    }

    public function testConfigurationWithDatabase()
    {
        $this->acl->setWithDatabase($this->conn);
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
        $this->acl->setWithDatabase($this->conn);
        $this->acl->setTable('users');
        $this->acl->setFieldUser('user');
        $this->acl->setFieldPassword('password');

        $this->assertTrue($this->acl->verify('admin', '1234'));
    }

    /**
     * @expectedException \Toneladas\Exceptions\UserWrongException
     * @expectedExceptionMessage User is wrong
     */
    public function testVerifyUserWithDatabaseUserWrong()
    {
        $this->acl->setWithDatabase($this->conn);
        $this->acl->setTable('users');
        $this->acl->setFieldUser('user');
        $this->acl->setFieldPassword('password');

        $this->assertTrue($this->acl->verify('otheruser', '1234'));
    }

    /**
     * @expectedException \Toneladas\Exceptions\PasswordWrongException
     * @expectedExceptionMessage Password is wrong
     */
    public function testVerifyUserWithDatabasePasswordWrong()
    {
        $this->acl->setWithDatabase($this->conn);
        $this->acl->setTable('users');
        $this->acl->setFieldUser('user');
        $this->acl->setFieldPassword('password');

        $this->assertTrue($this->acl->verify('admin', 'abcd'));
    }

    public function testVerifyUserWithEmail()
    {
        $this->acl->setWithDatabase($this->conn);
        $this->acl->isEmail();
        $this->acl->setTable('users');
        $this->acl->setFieldUser('user');
        $this->acl->setFieldPassword('password');

        $this->assertTrue($this->acl->verify('admin@admin.com', '1234'));
    }

    /**
     * @expectedException \Toneladas\Exceptions\EmailInvalidException
     * @expectedExceptionMessage Email is not valid
     */
    public function testVerifyUserWithEmailWrong()
    {
        $this->acl->setWithDatabase($this->conn);
        $this->acl->isEmail();
        $this->acl->setTable('users');
        $this->acl->setFieldUser('user');
        $this->acl->setFieldPassword('password');

        $this->assertTrue($this->acl->verify('admin@admincom', '1234'));
    }
}
