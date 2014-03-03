<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @todo Увеличивать кол-во чатов при добавлении чата
 */
/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\AgentRepository",
 *      collection="agents",
 *      requireIndexes=false
 *  )
 */
class Agent
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
     * @MongoDB\String
     */
    private $job_title;

    /**
     * @Assert\NotBlank
     * @MongoDB\Int
     */
    private $status;

    /**
     * @Assert\NotBlank
     * @MongoDB\Int
     */
    private $type;

    /**
     * @Assert\NotBlank
     * @MongoDB\Boolean
     */
    private $accept_chats;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Widget", cascade={"persist", "merge", "detach"}, inversedBy="agents")
     */
    private $widget;

    /* =============== References =============== */

    /**
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Person", mappedBy="agent")
     */
    private $person;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\Chat", mappedBy="operator")
     */
    private $chats;

    /* =============== Constants =============== */

    const TYPE_OWNER         = 1;
    const TYPE_ADMINISTRATOR = 2;
    const TYPE_OPERATOR      = 3;

    static public function getTypes()
    {
        return [
            self::TYPE_OWNER,
            self::TYPE_ADMINISTRATOR,
            self::TYPE_OPERATOR
        ];
    }

    const STATUS_DEFAULT = 1;
    const STATUS_BLOCKED = 2;
    const STATUS_DELETED = 3;

    static public function getStatuses()
    {
        return [
            self::STATUS_DEFAULT,
            self::STATUS_BLOCKED,
            self::STATUS_DELETED
        ];
    }

    /* =============== General =============== */

    public function __construct()
    {
        $this->uid = uniqid();
        $this->type = self::TYPE_ADMINISTRATOR;
        $this->status = self::STATUS_DEFAULT;
        $this->accept_chats = true;
        $this->chats = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->uid;
    }

    public function toArray()
    {
        $return = [
            'uid' => $this->uid,
            'job_title' => $this->job_title,
            'status' => $this->status,
            'type' => $this->type,
            'accept_chats' => $this->accept_chats
        ];

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
     * Set jobTitle
     *
     * @param string $jobTitle
     * @return self
     */
    public function setJobTitle($jobTitle)
    {
        $this->job_title = $jobTitle;
        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string $jobTitle
     */
    public function getJobTitle()
    {
        return $this->job_title;
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
     * Set type
     *
     * @param int $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return int $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set acceptChats
     *
     * @param boolean $acceptChats
     * @return self
     */
    public function setAcceptChats($acceptChats)
    {
        $this->accept_chats = $acceptChats;
        return $this;
    }

    /**
     * Get acceptChats
     *
     * @return boolean $acceptChats
     */
    public function getAcceptChats()
    {
        return $this->accept_chats;
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
     * @return \Regidium\CommonBundle\Document\Person $person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Add chat
     *
     * @param Regidium\CommonBundle\Document\Chat $chat
     */
    public function addChat(\Regidium\CommonBundle\Document\Chat $chat)
    {
        $this->chats[] = $chat;
    }

    /**
     * Remove chat
     *
     * @param Regidium\CommonBundle\Document\Chat $chat
     */
    public function removeChat(\Regidium\CommonBundle\Document\Chat $chat)
    {
        $this->chats->removeElement($chat);
    }

    /**
     * Get chats
     *
     * @return Doctrine\Common\Collections\Collection $chats
     */
    public function getChats()
    {
        return $this->chats;
    }
}
