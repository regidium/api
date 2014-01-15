<?php

namespace Regidium\ChatBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\Interfaces\IdInterface;
use Regidium\CommonBundle\Document\Interfaces\UidInterface;
use Regidium\CommonBundle\Document\Interfaces\PeriodInterface;

use Regidium\UserBundle\Document\User;
use Regidium\AgentBundle\Document\Agent;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\ChatBundle\Repository\ChatRepository",
 *      collection="chats",
 *      requireIndexes=false
 *  )
 *
 */
class Chat implements IdInterface, UidInterface, PeriodInterface
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
     * @MongoDB\ReferenceMany(targetDocument="ChatMessage", mappedBy="chat")
     */
    private $chat_messages;

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
     * @MongoDB\ReferenceOne(targetDocument="Regidium\UserBundle\Document\User", cascade={"refresh"}, inversedBy="chats")
     */
    private $user;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\AgentBundle\Document\Agent", cascade={"refresh"}, inversedBy="chats")
     */
    private $agent;

    /**
     * @Assert\NotBlank
     * @MongoDB\Int
     */
    private $user_status;

    /**
     * @MongoDB\String
     */
    private $agent_status;

    const STATUS_PENDING = 1;
    const STATUS_DEFAULT = 2;
    const STATUS_ARCHIVED = 3;
    const STATUS_DELETED = 4;

    static public function getStatuses()
    {
        return array(
                self::STATUS_PENDING,
                self::STATUS_DEFAULT,
                self::STATUS_ARCHIVED,
                self::STATUS_DELETED
            );
    }

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setStarted(time());
        $this->setUserStatus(self::STATUS_DEFAULT);
        $this->setAgentStatus(self::STATUS_PENDING);
    }

    public function __toString()
    {
        return $this->uid;
    }

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
     * @return id $id
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
     * Add chat message
     *
     * @param ChatMessage $chat_message
     */
    public function addChatMessage(ChatMessage $chat_message)
    {
        $this->chat_messages[] = $chat_message;
    }

    /**
     * Remove chat message
     *
     * @param ChatMessage $chat_message
     */
    public function removeChatMessage(ChatMessage $chat_message)
    {
        $this->chat_messages->removeElement($chat_message);
    }

    /**
     * Get chatMessages
     *
     * @return \Doctrine\Common\Collections\Collection $chat_messages
     */
    public function getChatMessages()
    {
        return $this->chat_messages;
    }

    /**
     * Set started
     *
     * @param int|string|\DateTime|null $started
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
     * Get started
     *
     * @return array $started
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * Set ended
     *
     * @param int|string|\DateTime|null $ended
     * @return self
     */
    public function setEnded($ended)
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
     * Get ended
     *
     * @return array $ended
     */
    public function getEnded()
    {
        return $this->ended;
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
     * Set userStatus
     *
     * @param int $status
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setUserStatus($status)
    {
        if (!in_array($status, self::getStatuses())) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $this->user_status = $status;
        return $this;
    }

    /**
     * Get user status
     *
     * @return int $user_status
     */
    public function getUserStatus()
    {
        return $this->user_status;
    }

    /**
     * Set agent status
     *
     * @param int $status
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setAgentStatus($status)
    {
        if (!in_array($status, self::getStatuses())) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $this->agent_status = $status;
        return $this;
    }

    /**
     * Get agent status
     *
     * @return string $agent_status
     */
    public function getAgentStatus()
    {
        return $this->agent_status;
    }
}
