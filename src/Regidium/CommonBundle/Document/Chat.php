<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Regidium\CommonBundle\Document\User;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\ChatRepository",
 *      collection="chats",
 *      requireIndexes=false
 *  )
 *
 */
class Chat
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(safe="true")
     */
    private $uid;

    /**
     * @MongoDB\String
     */
    private $model_type;

    /**
     * @MongoDB\Timestamp
     */
    private $started;

    /**
     * @MongoDB\Timestamp
     */
    private $ended;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne
     */
    private $owner;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Agent", cascade={"all"}, inversedBy="chats")
     */
    private $agent;

    /**
     * @Assert\NotBlank
     * @MongoDB\Int
     */
    private $owner_status;

    /**
     * @MongoDB\String
     */
    private $agent_status;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Client", cascade={"all"}, inversedBy="chats")
     */
    protected $client;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\ChatMessage", mappedBy="chat")
     */
    private $messages;

    /* =============== Constants =============== */

    const STATUS_PENDING  = 1;
    const STATUS_DEFAULT  = 2;
    const STATUS_ARCHIVED = 3;
    const STATUS_DELETED  = 4;

    static public function getStatuses()
    {
        return array(
                self::STATUS_PENDING,
                self::STATUS_DEFAULT,
                self::STATUS_ARCHIVED,
                self::STATUS_DELETED
            );
    }

    /* =============== General =============== */

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setStarted(time());
        $this->setUserStatus(self::STATUS_DEFAULT);
        $this->setAgentStatus(self::STATUS_PENDING);

        $this->messages = new ArrayCollection();

        $this->setModelType('chat');
    }

    public function __toString()
    {
        return $this->uid;
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
     * Set uid
     *
     * @param string $uid
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
     * @return string $uid
     */
    public function getUid()
    {
        return $this->uid;
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
     * Add message
     *
     * @param Regidium\CommonBundle\Document\ChatMessage $message
     */
    public function addMessage(\Regidium\CommonBundle\Document\ChatMessage $message)
    {
        $this->messages[] = $message;
    }

    /**
     * Remove message
     *
     * @param Regidium\CommonBundle\Document\ChatMessage $message
     */
    public function removeMessage(\Regidium\CommonBundle\Document\ChatMessage $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return Doctrine\Common\Collections\Collection $messages
     */
    public function getMessages()
    {
        return $this->messages;
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
     * Set owner
     *
     * @param $owner
     * @return self
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get owner
     *
     * @return $owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set agent
     *
     * @param Regidium\CommonBundle\Document\Agent $agent
     * @return self
     */
    public function setAgent(\Regidium\CommonBundle\Document\Agent $agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * Get agent
     *
     * @return Regidium\CommonBundle\Document\Agent $agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set ownerStatus
     *
     * @param int $ownerStatus
     * @return self
     */
    public function setOwnerStatus($ownerStatus)
    {
        $this->owner_status = $ownerStatus;
        return $this;
    }

    /**
     * Get ownerStatus
     *
     * @return int $ownerStatus
     */
    public function getOwnerStatus()
    {
        return $this->owner_status;
    }

    /**
     * Set agentStatus
     *
     * @param string $agentStatus
     * @return self
     */
    public function setAgentStatus($agentStatus)
    {
        $this->agent_status = $agentStatus;
        return $this;
    }

    /**
     * Get agentStatus
     *
     * @return string $agentStatus
     */
    public function getAgentStatus()
    {
        return $this->agent_status;
    }

    /**
     * Set client
     *
     * @param Regidium\CommonBundle\Document\Client $client
     * @return self
     */
    public function setClient(\Regidium\CommonBundle\Document\Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get client
     *
     * @return Regidium\CommonBundle\Document\Client $client
     */
    public function getClient()
    {
        return $this->client;
    }
}
