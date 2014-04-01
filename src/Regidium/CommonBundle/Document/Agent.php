<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

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
    private $first_name;

    /**
     * @MongoDB\String
     */
    private $last_name;

    /**
     * @MongoDB\String
     */
    private $avatar;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(safe="true")
     */
    private $email;

    /**
     * @MongoDB\String
     */
    private $password;

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
     * @MongoDB\Hash
     */
    private $external_service;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Widget", cascade={"persist", "merge", "detach"}, inversedBy="agents")
     */
    private $widget;

    /* =============== References =============== */

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\Chat", mappedBy="agent")
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
        $this->job_title = '';
        $this->type = self::TYPE_ADMINISTRATOR;
        $this->status = self::STATUS_DEFAULT;
        $this->accept_chats = true;
        $this->external_service = [];

        $this->chats = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->uid;
    }

    public function toArray(array $options = [])
    {
        $return = [
            'uid' => $this->uid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'avatar' => $this->avatar,
            'email' => $this->email,
            'job_title' => $this->job_title,
            'status' => $this->status,
            'type' => $this->type,
            'accept_chats' => $this->accept_chats
        ];

        if (in_array('widget', $options)) {
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
     * Set firstName
     *
     * @param string $firstName
     * @return self
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string $firstName
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return self
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string $lastName
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return self
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return string $avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
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
     * Set externalService
     *
     * @param hash $externalService
     * @return self
     */
    public function setExternalService($externalService)
    {
        $this->external_service = $externalService;
        return $this;
    }

    /**
     * Get externalService
     *
     * @return hash $externalService
     */
    public function getExternalService()
    {
        return $this->external_service;
    }

    /**
     * Set widget
     *
     * @param Regidium\CommonBundle\Document\Widget $widget
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
     * @return Regidium\CommonBundle\Document\Widget $widget
     */
    public function getWidget()
    {
        return $this->widget;
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
