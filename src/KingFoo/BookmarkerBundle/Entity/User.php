<?php

namespace KingFoo\BookmarkerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="KingFoo\BookmarkerBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\Column(type="simple_array")
     */
    protected $roles;

    function __construct()
    {
        $this->roles = array('ROLE_USER');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function addRole($role)
    {
        $this->roles[] = $role;
        $this->roles = array_unique($this->roles); // Make sure a role does not get added more than once
        return $this;
    }

    public function clearRoles()
    {
        $this->roles = array('ROLE_USER');
        return $this;
    }

    public function getSalt()
    {
        return null; // We use Bcrypt, so a salt is irrelevant
    }

    public function eraseCredentials()
    {
        // Remove any sensitive data before serialization
    }
}
