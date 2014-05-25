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
     * @MongoDB\Index
     * @MongoDB\String
     * @MongoDB\UniqueIndex(safe="true")
     */
    private $uid;

    /**
     * @MongoDB\Index
     * @MongoDB\Int
     */
    private $started_at;

    /**
     * @MongoDB\Index
     * @MongoDB\Int
     */
    private $ended_at;

    /**
     * @Assert\NotBlank
     * @MongoDB\Index
     * @MongoDB\Int
     */
    private $status;

    /**
     * @Assert\NotBlank
     * @MongoDB\Index
     * @MongoDB\Int
     */
    private $old_status;

    /**
     * @MongoDB\String
     */
    private $referrer;

    /**
     * @MongoDB\String
     */
    private $current_url;

    /**
     * @MongoDB\Boolean
     */
    private $opened;

    /**
     * @MongoDB\EmbedOne(targetDocument="Regidium\CommonBundle\Document\User", strategy="set")
     */
    private $user;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Agent", cascade={"persist", "merge", "detach"}, inversedBy="chats")
     */
    private $agent;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Widget", cascade={"persist", "merge", "detach"}, inversedBy="chats")
     */
    private $widget;

    /* =============== Embedded =============== */

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\ChatMessage", mappedBy="chat")
     */
    private $messages;

    /* =============== Constants =============== */

    const STATUS_ONLINE   = 1;
    const STATUS_CHATTING = 2;
    const STATUS_OFFLINE  = 3;

    static public function getStatuses()
    {
        return [
            self::STATUS_ONLINE,
            self::STATUS_CHATTING,
            self::STATUS_OFFLINE
        ];
    }

    /* =============== General =============== */

    public function __construct()
    {
        $this->uid = uniqid();
        $this->started_at = time();
        $this->opened = false;
        $this->status = self::STATUS_ONLINE;

        $this->user = [];
        $this->messages = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->uid;
    }

    public function toArray(array $options = [])
    {
        $return = [
            'uid' => $this->uid,
            'opened' => $this->opened,
            'status' => $this->status,
            'referrer' => $this->referrer,
            'current_url' => $this->current_url,
            'old_status' => $this->old_status,
            'user' => $this->user,
            'started_at' =>  $this->started_at
        ];

        if ($this->ended_at) {
            $return['ended_at'] =  $this->ended_at;
        }

        $return['messages'] = [];
        foreach($this->messages as $message) {
            $return['messages'][] = $message->toArray();
        }

        if (isset($options['agent']) && $this->agent) {
            $return['agent'] = $this->agent->toArray();
        }

        // if (isset($options['messages'])) {
        //     $return['messages'] = [];
        //     foreach($this->messages as $message) {
        //         $return['messages'][] = $message->toArray();
        //     }
        // }

        if (isset($options['widget'])) {
            $return['widget'] = $this->widget->toArray();
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
     * @param int $startedAt
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
     * @return int $startedAt
     */
    public function getStartedAt()
    {
        return $this->started_at;
    }

    /**
     * Set endedAt
     *
     * @param int $endedAt
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
     * @return int $endedAt
     */
    public function getEndedAt()
    {
        return $this->ended_at;
    }

    /**
     * Set status
     *
     * @param int $status
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return int $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set oldStatus
     *
     * @param int $oldStatus
     * @return self
     */
    public function setOldStatus($oldStatus)
    {
        $this->old_status = $oldStatus;
        return $this;
    }

    /**
     * Get oldStatus
     *
     * @return int $oldStatus
     */
    public function getOldStatus()
    {
        return $this->old_status;
    }

    /**
     * Set referrer
     *
     * @param string $referrer
     * @return self
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;
        return $this;
    }

    /**
     * Get referrer
     *
     * @return string $referrer
     */
    public function getReferrer()
    {
        return $this->referrer;
    }


    /**
     * Set current_url
     *
     * @param string $current_url
     * @return self
     */
    public function setCurrentUrl($current_url)
    {
        $this->current_url = $current_url;
        return $this;
    }

    /**
     * Get current_url
     *
     * @return string $current_url
     */
    public function getCurrentUrl()
    {
        return $this->current_url;
    }

    /**
     * Set opened
     *
     * @param boolean $opened
     * @return self
     */
    public function setOpened($opened)
    {
        $this->opened = $opened;
        return $this;
    }

    /**
     * Get opened
     *
     * @return boolean $opened
     */
    public function getOpened()
    {
        return $this->opened;
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
     * Set agent
     *
     * @param \Regidium\CommonBundle\Document\Agent $agent
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
     * @return \Regidium\CommonBundle\Document\Agent $agent
     */
    public function getAgent()
    {
        return $this->agent;
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
