<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\ChatMessageRepository",
 *      collection="chat_messages",
 *      requireIndexes=false
 *  )
 */
class ChatMessage
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
    private $created_at;

    /**
     * @MongoDB\Timestamp
     */
    private $updated_at;

    /**
     * @MongoDB\String
     */
    private $text;

    /**
     * @MongoDB\Boolean
     */
    private $archived;

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

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Person", cascade={"persist", "merge", "detach"}, inversedBy="output_messages")
     */
    private $sender;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Person", cascade={"persist", "merge", "detach"}, inversedBy="input_messages")
     */
    private $receiver;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Chat", cascade={"all"}, inversedBy="messages")
     */
    private $chat;

    /* =============== Constants =============== */

    const STATUS_NOT_READED = 1;
    const STATUS_DEFAULT = 2;
    const STATUS_ARCHIVED = 3;
    const STATUS_DELETED = 4;

    static public function getStatuses()
    {
        return [
            self::STATUS_NOT_READED,
            self::STATUS_DEFAULT,
            self::STATUS_ARCHIVED,
            self::STATUS_DELETED
        ];
    }

    /* =============== General =============== */

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setCreatedAt(time());
        $this->setUpdatedAt(time());
        $this->setSenderStatus(self::STATUS_DEFAULT);
        $this->setReceiverStatus(self::STATUS_NOT_READED);
        $this->setArchived(false);
    }

    public function __toString()
    {
        return $this->text;
    }

    public function toArray()
    {
        $return = [
            'uid' => $this->uid,
            'created' => $this->created_at,
            'updated' => $this->updated_at,
            'text' => $this->text,
            'sender' => $this->sender,
            'sender_status' => $this->sender_status
        ];

        if ($this->receiver) {
            $return['receiver'] = $this->receiver->toArray();
            $return['receiver_status'] = $this->receiver_status;
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
     * Set createdAt
     *
     * @param timestamp $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return timestamp $createdAt
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updatedAt
     *
     * @param timestamp $updatedAt
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return timestamp $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return self
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
     * Set senderStatus
     *
     * @param int $senderStatus
     * @return self
     */
    public function setSenderStatus($senderStatus)
    {
        $this->sender_status = $senderStatus;
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
     * Set receiverStatus
     *
     * @param int $receiverStatus
     * @return self
     */
    public function setReceiverStatus($receiverStatus)
    {
        $this->receiver_status = $receiverStatus;
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

    /**
     * Set sender
     *
     * @param Regidium\CommonBundle\Document\Person $sender
     * @return self
     */
    public function setSender(\Regidium\CommonBundle\Document\Person $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Get sender
     *
     * @return Regidium\CommonBundle\Document\Person $sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param Regidium\CommonBundle\Document\Person $receiver
     * @return self
     */
    public function setReceiver(\Regidium\CommonBundle\Document\Person $receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * Get receiver
     *
     * @return Regidium\CommonBundle\Document\Person $receiver
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Set archived
     *
     * @param boolean $archived
     * @return self
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
        return $this;
    }

    /**
     * Get archived
     *
     * @return boolean $archived
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * Set chat
     *
     * @param \Regidium\CommonBundle\Document\Chat $chat
     * @return self
     */
    public function setChat(\Regidium\CommonBundle\Document\Chat $chat)
    {
        $this->chat = $chat;
        return $this;
    }

    /**
     * Get chat
     *
     * @return \Regidium\CommonBundle\Document\Chat $chat
     */
    public function getChat()
    {
        return $this->chat;
    }
}
