<?php

namespace Regidium\ChatBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\Interfaces\IdInterface;
use Regidium\CommonBundle\Document\Interfaces\UidInterface;
use Regidium\CommonBundle\Document\Interfaces\TimestampInterface;

use Regidium\UserBundle\Document\User;
use Regidium\AgentBundle\Document\Agent;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\ChatBundle\Repository\ChatMessageRepository",
 *      collection="chat_messages",
 *      requireIndexes=false
 *  )
 *
 */
class ChatMessage implements IdInterface, UidInterface, TimestampInterface
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
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\ChatBundle\Document\Chat", cascade={"refresh"}, inversedBy="messages")
     */
    private $chat;

    /**
     * @MongoDB\Timestamp
     */
    private $created;

    /**
     * @MongoDB\Timestamp
     */
    private $updated;

    /**
     * @MongoDB\String
     */
    private $text;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(cascade={"refresh"})
     */
    private $sender;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(cascade={"refresh"})
     */
    private $receiver;

    /**
     * @Assert\NotBlank
     * @MongoDB\Int
     */
    private $sender_status;

    /**
     * @Assert\NotBlank
     * @MongoDB\Int
     */
    private $receiver_status;

    const STATUS_NOT_READED = 1;
    const STATUS_DEFAULT = 2;
    const STATUS_ARCHIVED = 3;
    const STATUS_DELETED = 4;

    static public function getStatuses()
    {
        return array(
            self::STATUS_NOT_READED,
            self::STATUS_DEFAULT,
            self::STATUS_ARCHIVED,
            self::STATUS_DELETED
        );
    }

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setCreated(time());
        $this->setUpdated(time());
        $this->setSenderStatus(self::STATUS_DEFAULT);
        $this->setReceiverStatus(self::STATUS_NOT_READED);
    }

    public function __toString()
    {
        return $this->text;
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
     * Set chat
     *
     * @param Chat $chat
     * @return self
     */
    public function setChat(Chat $chat)
    {
        $this->chat = $chat;
        return $this;
    }

    /**
     * Get chat
     *
     * @return Chat $chat
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * Set created
     *
     * @param int|string|\DateTime|null $created
     * @return self
     */
    public function setCreated($created)
    {
        if (is_string($created)) {
            $created = strtotime($created);
        } elseif ($created instanceof \DateTime) {
            $created = $created->getTimestamp();
        } elseif(!is_integer($created)) {
            $created = time();
        }

        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return array $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param int|string|\DateTime|null $updated
     * @return self
     */
    public function setUpdated($updated)
    {
        if (is_string($updated)) {
            $updated = strtotime($updated);
        } elseif ($updated instanceof \DateTime) {
            $updated = $updated->getTimestamp();
        } elseif(!is_integer($updated)) {
            $updated = time();
        }

        $this->updated = $updated;
        return $this;
    }

    /**
     * Get updated
     *
     * @return array $updated
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return self
     *
     * @todo HTML ESCAPE
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text
     *
     * @return string $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set sender
     *
     * @param User|Agent $sender
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setSender($sender)
    {
        if (!$sender instanceof User && !$sender instanceof Agent) {
            throw new \InvalidArgumentException('Invalid sender class: '.get_class($sender));
        }

        $this->sender = $sender;
        return $this;
    }

    /**
     * Get sender
     *
     * @return User|Agent $sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param User|Agent $receiver
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setReceiver($receiver)
    {
        if (!$receiver instanceof User && !$receiver instanceof Agent) {
            throw new \InvalidArgumentException('Invalid receiver class: '.get_class($receiver));
        }

        $this->receiver = $receiver;
        return $this;
    }

    /**
     * Get receiver
     *
     * @return User|Agent $receiver
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Set sender status
     *
     * @param int $status
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setSenderStatus($status)
    {
        if (!in_array($status, self::getStatuses())) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $this->sender_status = $status;
        return $this;
    }

    /**
     * Get senderStatus
     *
     * @return int $senderStatus
     */
    public function getSenderStatus()
    {
        return $this->sender_status;
    }

    /**
     * Set receiver status
     *
     * @param int $status
     *
     * @throws \InvalidArgumentException
     * @return self
     */
    public function setReceiverStatus($status)
    {
        if (!in_array($status, self::getStatuses())) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        $this->receiver_status = $status;
        return $this;
    }

    /**
     * Get receiverStatus
     *
     * @return int $receiverStatus
     */
    public function getReceiverStatus()
    {
        return $this->receiver_status;
    }
}
