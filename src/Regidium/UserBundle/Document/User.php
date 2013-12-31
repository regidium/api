<?php

namespace Regidium\UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\Interfaces\IdableInterface;
use Regidium\CommonBundle\Document\Interfaces\StatebleInteface;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\UserBundle\Repository\UserRepository",
 *      collection="users",
 *      requireIndexes=true
 *  )
 */
class User implements IdableInterface, StatebleInteface
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $fullname;

    /**
     * @Assert\NotBlank
     * @MongoDB\String @MongoDB\UniqueIndex(safe="true")
     */
    protected $email;

    /**
     * @MongoDB\String
     */
    protected $password;

    /**
     * @Assert\NotBlank
     * @MongoDB\Int
     */
    protected $state;

    const STATE_DEFAULT = 1;
    const STATE_BLOCKED = 2;
    const STATE_DELETED = 3;

    static public function getStates()
    {
        return array(
                self::STATE_DEFAULT,
                self::STATE_BLOCKED,
                self::STATE_DELETED
            );
    }

    public function __construct()
    {
        $this->setState(self::STATE_DEFAULT);
    }

    public function __toString()
    {
        return $this->fullname;
    }

    /**
     * Set id
     *
     * @param int $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return self
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
        return $this;
    }

    /**
     * Get fullname
     *
     * @return string $fullname
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = strtolower($email);
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setState($state)
    {
        if (!in_array($state, self::getStates())) {
            throw new \InvalidArgumentException("Invalid state: {$state}");
        }
        $this->state = $state;
        return $this;
    }

    /**
     * Get state
     *
     * @return int $state
     */
    public function getState()
    {
        return $this->state;
    }
}