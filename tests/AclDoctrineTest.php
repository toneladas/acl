<?php

namespace tests;

use Doctrine\ORM\Tools\SchemaTool;

class AclDoctrineTest extends \PHPUnit_Framework_TestCase
{
    protected $entityManager = null;
    private $acl;

    protected function setUp()
    {
        $entityManager = $this->getEntityManager();
        $tool = new SchemaTool($entityManager);
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();
        $tool->createSchema($classes);

        $user = new \tests\Entities\User;
        $user->setUser('admin');
        $user->setPassword('1234');

        $entityManager->persist($user);

        $userEmail = new \tests\Entities\User;
        $userEmail->setUser('admin@admin.com');
        $userEmail->setPassword('1234');

        $entityManager->persist($userEmail);
        $entityManager->flush();

        $this->acl = new \Toneladas\Acl();
        parent::setup();
    }

    protected function tearDown()
    {
        $entityManager = $this->getEntityManager();
        $tool = new SchemaTool($entityManager);
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();

        $tool->dropSchema($classes);
        parent::tearDown();
    }

    protected function getEntityManager()
    {
        if (!$this->entityManager) {
            $this->entityManager = require __DIR__ . '/bootstrap.php';
        }

        return $this->entityManager;
    }

    public function testConfigurationWithDoctrine()
    {
        $this->acl->setWithDoctrine($this->entityManager);
        $this->acl->setEntity('\tests\Entities\User');
        $this->acl->setFieldUser('user');
        $this->acl->setMethodPassword('getPassword');

        $this->assertEquals('\tests\Entities\User', $this->acl->getEntity());
        $this->assertEquals('getPassword', $this->acl->getMethodPassword());
    }

    /**
     * @depends testConfigurationWithDoctrine
     */
    public function testVerifyUserWithDoctrine()
    {
        $this->acl->setWithDoctrine($this->entityManager);
        $this->acl->setEntity('\tests\Entities\User');
        $this->acl->setFieldUser('user');
        $this->acl->setMethodPassword('getPassword');

        $this->assertTrue($this->acl->verify('admin', '1234'));
    }

    /**
     * @depends testConfigurationWithDoctrine
     */
    public function testVerifyUserWithDoctrineWithEmail()
    {
        $this->acl->setWithDoctrine($this->entityManager);
        $this->acl->setEntity('\tests\Entities\User');
        $this->acl->setFieldUser('user');
        $this->acl->setMethodPassword('getPassword');
        $this->acl->isEmail();

        $this->assertTrue($this->acl->verify('admin@admin.com', '1234'));
    }

    /**
     * @expectedException \Toneladas\Exceptions\EmailInvalidException
     * @expectedExceptionMessage Email is not valid
     */
    public function testVerifyUserWithEmailWrong()
    {
        $this->acl->setWithDoctrine($this->entityManager);
        $this->acl->setEntity('\tests\Entities\User');
        $this->acl->setFieldUser('user');
        $this->acl->setMethodPassword('getPassword');
        $this->acl->isEmail();

        $this->assertTrue($this->acl->verify('admin@admincom', '1234'));
    }

    /**
     * @expectedException \Toneladas\Exceptions\UserWrongException
     * @expectedExceptionMessage User is wrong
     */
    public function testVerifyUserWithDatabaseUserWrong()
    {
        $this->acl->setWithDoctrine($this->entityManager);
        $this->acl->setEntity('\tests\Entities\User');
        $this->acl->setFieldUser('user');
        $this->acl->setMethodPassword('getPassword');

        $this->assertTrue($this->acl->verify('otheruser', '1234'));
    }

    /**
     * @expectedException \Toneladas\Exceptions\PasswordWrongException
     * @expectedExceptionMessage Password is wrong
     */
    public function testVerifyUserWithDatabasePasswordWrong()
    {
        $this->acl->setWithDoctrine($this->entityManager);
        $this->acl->setEntity('\tests\Entities\User');
        $this->acl->setFieldUser('user');
        $this->acl->setMethodPassword('getPassword');

        $this->assertTrue($this->acl->verify('admin', 'abcd'));
    }
}
