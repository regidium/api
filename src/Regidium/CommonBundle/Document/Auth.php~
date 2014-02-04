<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\User;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\AuthRepository",
 *      collection="auths",
 *      requireIndexes=false
 *  )
 */
class Auth
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    private $model_type;

    /**
     * @MongoDB\Timestamp
     */
    protected $started;

    /**
     * @MongoDB\Timestamp
     */
    protected $ended;

    /**
     * @MongoDB\Boolean
     */
    protected $remember;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(safe="true")
     */
    protected $token;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Person", cascade={"all"}, mappedBy="auths")
     */
    private $person;

    /* =============== General =============== */

    public function __construct()
    {
        $this->setStarted(time());
        $this->setRemember(false);
        $this->setToken(uniqid('r', true));

        $this->setModelType('auth');
    }

    public function __toString()
    {
        return '';
    }

    /* =============== Get/Set=============== */

    /**
     * Set id
     *
     * @param string $id
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
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set modelType
     *
     * @param string $modelType
     * @return self
     */
    public function setModelType($modelType)
    {
        $this->model_type = $modelType;
        return $this;
    }

    /**
     * Get modelType
     *
     * @return string $modelType
     */
    public function getModelType()
    {
        return $this->model_type;
    }

    /**
     * Set user
     *
     * @param Regidium\CommonBundle\Document\User $user
     * @return self
     */
    public function setUser(\Regidium\CommonBundle\Document\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return Regidium\CommonBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set started
     *
     * @param timestamp $started
     * @return self
     */
    public function setStarted($started)
    {
        $this->started = $started;
        return $this;
    }

    /**
     * Get started
     *
     * @return timestamp $started
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * Set ended
     *
     * @param timestamp $ended
     * @return self
     */
    public function setEnded($ended)
    {
        $this->ended = $ended;
        return $this;
    }

    /**
     * Get ended
     *
     * @return timestamp $ended
     */
    public function getEnded()
    {
        return $this->ended;
    }

    /**
     * Set remember
     *
     * @param boolean $remember
     * @return self
     */
    public function setRemember($remember)
    {
        $this->remember = $remember;
        return $this;
    }

    /**
     * Get remember
     *
     * @return boolean $remember
     */
    public function getRemember()
    {
        return $this->remember;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return self
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token
     *
     * @return string $token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set person
     *
     * @param Regidium\CommonBundle\Document\Person $person
     * @return self
     */
    public function setPerson(\Regidium\CommonBundle\Document\Person $person)
    {
        $this->person = $person;
        return $this;
    }

    /**
     * Get person
     *
     * @return Regidium\CommonBundle\Document\Person $person
     */
    public function getPerson()
    {
        return $this->person;
    }
}
