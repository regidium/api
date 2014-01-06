<?php

namespace Regidium\AgentBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\Interfaces\IdableInterface;
use Regidium\CommonBundle\Document\Interfaces\StatebleInteface;

use Regidium\AuthBundle\Document\Auth;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\AgentBundle\Repository\AgentRepository",
 *      collection="agents",
 *      requireIndexes=false
 *  )
 */
class Agent implements IdableInterface, StatebleInteface
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String @MongoDB\UniqueIndex(safe="true")
     */
    protected $uid;

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

    /**
     * @MongoDB\Hash
     */
    protected $external_service;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\AuthBundle\Document\Auth", mappedBy="user")
    */
    protected $auths;

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
        $this->setUid(uniqid());
        $this->setState(self::STATE_DEFAULT);
        $this->setExternalService([]);
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
     * Set uid
     *
     * @param int $uid
     * @return self
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * Get uid
     *
     * @return uid $uid
     */
    public function getUid()
    {
        return $this->uid;
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

    /**
     * Set externalService
     *
     * @param array $externalService
     * @return self
     */
    public function setExternalService($externalService)
    {
        $this->external_service = $externalService;
        return $this;
    }

    /**
     * Get externalService
     *
     * @return array $externalService
     */
    public function getExternalService()
    {
        return $this->external_service;
    }

    /**
     * Add auth
     *
     * @param Auth $auth
     */
    public function addAuth(Auth $auth)
    {
        $this->auths[] = $auth;
    }

    /**
     * Remove auth
     *
     * @param Auth $auth
     */
    public function removeAuth(Auth $auth)
    {
        $this->auths->removeElement($auth);
    }

    /**
     * Get auths
     *
     * @return \Doctrine\Common\Collections\Collection $auths
     */
    public function getAuths()
    {
        return $this->auths;
    }
}
