<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\User;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\ChatMessageRepository",
 *      collection="chat_messages",
 *      requireIndexes=false
 *  )
 *
 */
class ChatMessage
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
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Person", cascade={"all"}, inversedBy="output_messages")
     */
    private $sender;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Person", cascade={"all"}, inversedBy="input_messages")
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
        return array(
            self::STATUS_NOT_READED,
            self::STATUS_DEFAULT,
            self::STATUS_ARCHIVED,
            self::STATUS_DELETED
        );
    }

    /* =============== General =============== */

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setCreated(time());
        $this->setUpdated(time());
        $this->setSenderStatus(self::STATUS_DEFAULT);
        $this->setReceiverStatus(self::STATUS_NOT_READED);

        $this->setModelType('chat_message');
    }

    public function __toString()
    {
        return $this->text;
    }

    public function toArray()
    {
        $return = [
            'uid' => $this->uid,
            'created' => $this->created,
            'updated' => $this->updated,
            'text' => $this->text,
            'sender' => $this->sender,
            'sender_status' => $this->sender_status,
            'receiver_status' => $this->receiver_status
        ];

        if ($this->receiver) {
            $return['receiver'] = $this->receiver->toArray();
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
     * Set chat
     *
     * @param Regidium\CommonBundle\Document\Chat $chat
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
     * @return Regidium\CommonBundle\Document\Chat $chat
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * Set created
     *
     * @param timestamp $created
     * @return self
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return timestamp $created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param timestamp $updated
     * @return self
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * Get updated
     *
     * @return timestamp $updated
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
     * @param \Regidium\CommonBundle\Document\Person $sender
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
     * @return \Regidium\CommonBundle\Document\Person $sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set receiver
     *
     * @param \Regidium\CommonBundle\Document\Person $receiver
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
}
