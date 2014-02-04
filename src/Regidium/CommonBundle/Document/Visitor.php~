<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\VisitorRepository",
 *      collection="visitors",
 *      requireIndexes=false
 *  )
 */
class Visitor
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
     * @MongoDB\Int
     */
    private $status;

    /**
     * @MongoDB\String
     */
    private $os;

    /**
     * @MongoDB\String
     */
    private $browser;

    /**
     * @MongoDB\String
     */
    private $keyword;

    /**
     * @MongoDB\String
     */
    private $language;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Person", mappedBy="visitor")
     */
    protected $person;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Client", cascade={"all"}, inversedBy="visitors")
     */
    protected $client;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\Chat", mappedBy="owner")
     */
    protected $chats;

    /* =============== Constants =============== */

    const STATUS_DEFAULT = 1;
    const STATUS_BLOCKED = 2;
    const STATUS_DELETED = 3;

    static public function getStatuses()
    {
        return array(
                self::STATUS_DEFAULT,
                self::STATUS_BLOCKED,
                self::STATUS_DELETED
            );
    }

    /* =============== General =============== */

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setStatus(self::STATUS_DEFAULT);

        $this->chats = new ArrayCollection();

        $this->setModelType('visitor');
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
     * Set os
     *
     * @param string $os
     * @return self
     */
    public function setOs($os)
    {
        $this->os = $os;
        return $this;
    }

    /**
     * Get os
     *
     * @return string $os
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * Set browser
     *
     * @param string $browser
     * @return self
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
        return $this;
    }

    /**
     * Get browser
     *
     * @return string $browser
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     * @return self
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Get keyword
     *
     * @return string $keyword
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return self
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Get language
     *
     * @return string $language
     */
    public function getLanguage()
    {
        return $this->language;
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
     * @return Regidium\CommonBundle\Document\Person $person
     */
    public function getPerson()
    {
        return $this->person;
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
