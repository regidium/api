<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

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
    /* =============== Attributes =============== */

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
     * @MongoDB\Timestamp
     */
    private $started_at;

    /**
     * @MongoDB\Timestamp
     */
    private $ended_at;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\User", cascade={"persist", "merge", "detach"}, inversedBy="chats")
     */
    private $user;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Agent", cascade={"persist", "merge", "detach"}, inversedBy="chats")
     */
    private $operator;

    /**
     * @Assert\NotBlank
     * @MongoDB\Int
     */
    private $user_status;

    /**
     * @Assert\NotBlank
     * @MongoDB\Int
     */
    private $operator_status;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Widget", cascade={"persist", "merge", "detach"}, inversedBy="chats")
     */
    protected $widget;

    /* =============== Embedded =============== */

    /**
     * @MongoDB\EmbedMany(targetDocument="Regidium\CommonBundle\Document\ChatMessage", strategy="pushAll")
     */
    private $messages;

    /* =============== Constants =============== */

    const STATUS_PENDING  = 1;
    const STATUS_DEFAULT  = 2;
    const STATUS_ARCHIVED = 3;
    const STATUS_DELETED  = 4;

    static public function getStatuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_DEFAULT,
            self::STATUS_ARCHIVED,
            self::STATUS_DELETED
        ];
    }

    /* =============== General =============== */

    public function __construct()
    {
        $this->uid = uniqid();
        $this->started_at = time();
        $this->user_status = self::STATUS_DEFAULT;
        $this->operator_status = self::STATUS_PENDING;

        $this->messages = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->uid;
    }

    public function toArray()
    {
        $return = [
            'uid' => $this->uid,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'user' => $this->user->toArray(),
            'user_status' => $this->user_status,
            'widget' => $this->widget->toArray()
        ];

        if ($this->operator) {
            $return['operator'] = $this->operator->toArray();
            $return['operator_status'] = $this->operator_status;
        }

        return $return;
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
     * Set startedAt
     *
     * @param timestamp $startedAt
     * @return self
     */
    public function setStartedAt($startedAt)
    {
        $this->started_at = $startedAt;
        return $this;
    }

    /**
     * Get startedAt
     *
     * @return timestamp $startedAt
     */
    public function getStartedAt()
    {
        return $this->started_at;
    }

    /**
     * Set endedAt
     *
     * @param timestamp $endedAt
     * @return self
     */
    public function setEndedAt($endedAt)
    {
        $this->ended_at = $endedAt;
        return $this;
    }

    /**
     * Get endedAt
     *
     * @return timestamp $endedAt
     */
    public function getEndedAt()
    {
        return $this->ended_at;
    }

    /**
     * Set user
     *
     * @param \Regidium\CommonBundle\Document\User $user
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
     * @return \Regidium\CommonBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set operator
     *
     * @param \Regidium\CommonBundle\Document\Agent $operator
     * @return self
     */
    public function setOperator(\Regidium\CommonBundle\Document\Agent $operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Get operator
     *
     * @return \Regidium\CommonBundle\Document\Agent $operator
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set userStatus
     *
     * @param int $userStatus
     * @return self
     */
    public function setUserStatus($userStatus)
    {
        $this->user_status = $userStatus;
        return $this;
    }

    /**
     * Get userStatus
     *
     * @return int $userStatus
     */
    public function getUserStatus()
    {
        return $this->user_status;
    }

    /**
     * Set operatorStatus
     *
     * @param int $operatorStatus
     * @return self
     */
    public function setOperatorStatus($operatorStatus)
    {
        $this->operator_status = $operatorStatus;
        return $this;
    }

    /**
     * Get operatorStatus
     *
     * @return int $operatorStatus
     */
    public function getOperatorStatus()
    {
        return $this->operator_status;
    }

    /**
     * Set widget
     *
     * @param \Regidium\CommonBundle\Document\Widget $widget
     * @return self
     */
    public function setWidget(\Regidium\CommonBundle\Document\Widget $widget)
    {
        $this->widget = $widget;
        return $this;
    }

    /**
     * Get widget
     *
     * @return \Regidium\CommonBundle\Document\Widget $widget
     */
    public function getWidget()
    {
        return $this->widget;
    }

    /**
     * Add message
     *
     * @param \Regidium\CommonBundle\Document\ChatMessage $message
     */
    public function addMessage(\Regidium\CommonBundle\Document\ChatMessage $message)
    {
        $this->messages[] = $message;
    }

    /**
     * Remove message
     *
     * @param \Regidium\CommonBundle\Document\ChatMessage $message
     */
    public function removeMessage(\Regidium\CommonBundle\Document\ChatMessage $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection $messages
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
