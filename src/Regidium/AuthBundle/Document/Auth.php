<?php

namespace Regidium\AuthBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\Interfaces\IdInterface;
use Regidium\CommonBundle\Document\Interfaces\PeriodInterface;

use Regidium\UserBundle\Document\User;
use Regidium\AgentBundle\Document\Agent;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\AuthBundle\Repository\AuthRepository",
 *      collection="auths",
 *      requireIndexes=false
 *  )
 */
class Auth implements IdInterface, PeriodInterface
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\UserBundle\Document\User", cascade={"all"}, inversedBy="auths")
     */
    protected $user;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\AgentBundle\Document\Agent", cascade={"all"}, inversedBy="auths")
     */
    protected $agent;

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
     * @MongoDB\String @MongoDB\UniqueIndex(safe="true")
     */
    protected $token;

    public function __construct()
    {
        $this->setStarted(time());
        $this->setRemember(false);
        $this->setToken(uniqid('r', true));
    }

    public function __toString()
    {
        return '';
    }

    /**
     * Set user or agent
     *
     * @param User|Agent $owner
     * @return self
     */
    public function setOwner($owner)
    {
        if ($owner instanceof Agent) {
            $this->setAgent($owner);
        } elseif ($owner instanceof User) {
            $this->setUser($owner);
        }

        return $this;
    }

    /**
     * Get user or agent
     *
     * @return User|Agent
     */
    public function getOwner()
    {
        if ($this->user) {
            return $this->user;
        } elseif ($this->agent) {
            return $this->agent;
        } else {
            return null;
        }
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
     * Get started
     *
     * @return array $started
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * Set started
     *
     * @param int|string|\DateTime|null $started
     *
     * @return self
     */
    public function setStarted($started)
    {
        if (is_string($started)) {
            $started = strtotime($started);
        } elseif ($started instanceof \DateTime) {
            $started = $started->getTimestamp();
        } elseif(!is_integer($started)) {
            $started = time();
        }

        $this->started = $started;
        return $this;
    }

    /**
     * Get ended
     *
     * @return array ended
     */
    public function getEnded()
    {
        return $this->ended;
    }


    /**
     * Set ended
     *
     * @param int|string|\DateTime|null $ended
     *
     * @return self
     */
    public function setEnded($ended = null)
    {
        if (is_string($ended)) {
            $ended = strtotime($ended);
        } elseif ($ended instanceof \DateTime) {
            $ended = $ended->getTimestamp();
        } elseif(!is_integer($ended)) {
            $ended = time();
        }

        $this->ended = $ended;
        return $this;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return self
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set agent
     *
     * @param Agent $agent
     * @return self
     */
    public function setAgent(Agent $agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * Get agent
     *
     * @return Agent $agent
     */
    public function getAgent()
    {
        return $this->agent;
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
}
