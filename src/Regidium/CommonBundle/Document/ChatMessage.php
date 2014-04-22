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
 *
 * @MongoDB\HasLifecycleCallbacks
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
     * @MongoDB\Date
     */
    private $created_at;

    /**
     * @MongoDB\String
     */
    private $text;

    /**
     * @MongoDB\Boolean
     */
    private $readed;

    /**
     * @MongoDB\Boolean
     */
    private $archived;

    /**
     * @MongoDB\Int
     */
    private $sender_type;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Chat", cascade={"all"}, inversedBy="messages")
     */
    private $chat;

    /* =============== Constants =============== */

    const SENDER_TYPE_USER = 1;
    const SENDER_TYPE_AGENT = 2;
    const SENDER_TYPE_ROBOT = 3;

    static public function getSenderTypes()
    {
        return [
            self::SENDER_TYPE_USER,
            self::SENDER_TYPE_AGENT,
            self::SENDER_TYPE_ROBOT
        ];
    }

    /* =============== General =============== */

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setCreatedAt(time());
        $this->setReaded(false);
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
            'created_at' => $this->created_at,
            'readed' => $this->readed,
            'text' => $this->text,
            'sender_type' => $this->sender_type
        ];

        return $return;
    }

    /* =============== Get/Set =============== */

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
     * @param date $createdAt
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
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->created_at;
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
     * Set senderType
     *
     * @param int $senderType
     * @return self
     */
    public function setSenderType($senderType)
    {
        $this->sender_type = $senderType;
        return $this;
    }

    /**
     * Get senderType
     *
     * @return int $senderType
     */
    public function getSenderType()
    {
        return $this->sender_type;
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

    /**
     * Set readed
     *
     * @param boolean $readed
     * @return self
     */
    public function setReaded($readed)
    {
        $this->readed = $readed;
        return $this;
    }

    /**
     * Get readed
     *
     * @return boolean $readed
     */
    public function getReaded()
    {
        return $this->readed;
    }
}
