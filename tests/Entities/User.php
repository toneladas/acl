<?php

namespace tests\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=false)
     * @var string
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     * @var string
     */
    private $password;

    public function getId()
    {
        return $this->id;
    }

    public function setUser($user)
    {
        return $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setPassword($password)
    {
        $password = password_hash($password, \PASSWORD_BCRYPT, ['cost' => 12]);
        return $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
