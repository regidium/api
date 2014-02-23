<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Regidium\CommonBundle\Document\Plan;
use Regidium\CommonBundle\Document\PaymentMethod;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\WidgetRepository",
 *      collection="widgets",
 *      requireIndexes=false
 *  )
 */
class Widget
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
     * @MongoDB\String
     * @MongoDB\UniqueIndex(safe="true")
     */
    private $personal_account;

    /**
     * @MongoDB\String
     */
    protected $css;

    /**
     * @MongoDB\Float
     */
    private $balance;

    /**
     * @MongoDB\String
     */
    private $url;

    /**
     * @MongoDB\Int
     */
    private $status;

    /**
     * @MongoDB\Int
     */
    private $available_agents;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Plan", cascade={"all"}, inversedBy="widgets")
     */
    private $plan;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\Agent", mappedBy="widget")
     */
    private $agents;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\User", mappedBy="widget")
     */
    private $users;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\Chat", mappedBy="widget")
     */
    private $chats;

    /**
     * @MongoDB\Hash
     */
    private $settings;

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
        $this->setPersonalAccount(uniqid());
        $this->setBalance(0);
        $this->setAvailableAgents(0);
        $this->setStatus(self::STATUS_DEFAULT);

        $this->agents = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->settings = [
            'header_color' => '#ec1d23'
        ];

        $this->setModelType('widget');
    }

    public function __toString()
    {
        return $this->personal_account;
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
     * Set personalAccount
     *
     * @param string $personalAccount
     * @return self
     */
    public function setPersonalAccount($personalAccount)
    {
        $this->personal_account = $personalAccount;
        return $this;
    }

    /**
     * Get personalAccount
     *
     * @return string $personalAccount
     */
    public function getPersonalAccount()
    {
        return $this->personal_account;
    }

    /**
     * Set css
     *
     * @param string $css
     * @return self
     */
    public function setCss($css)
    {
        $this->css = $css;
        return $this;
    }

    /**
     * Get css
     *
     * @return string $css
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * Set balance
     *
     * @param float $balance
     * @return self
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * Get balance
     *
     * @return float $balance
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
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
     * Set availableAgents
     *
     * @param int $availableAgents
     * @return self
     */
    public function setAvailableAgents($availableAgents)
    {
        $this->available_agents = $availableAgents;
        return $this;
    }

    /**
     * Get availableAgents
     *
     * @return int $availableAgents
     */
    public function getAvailableAgents()
    {
        return $this->available_agents;
    }

    /**
     * Set plan
     *
     * @param Regidium\CommonBundle\Document\Plan $plan
     * @return self
     */
    public function setPlan(\Regidium\CommonBundle\Document\Plan $plan)
    {
        $this->plan = $plan;
        return $this;
    }

    /**
     * Get plan
     *
     * @return \Regidium\CommonBundle\Document\Plan $plan
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Add agent
     *
     * @param Regidium\CommonBundle\Document\Agent $agent
     */
    public function addAgent(\Regidium\CommonBundle\Document\Agent $agent)
    {
        $this->agents[] = $agent;
    }

    /**
     * Remove agent
     *
     * @param Regidium\CommonBundle\Document\Agent $agent
     */
    public function removeAgent(\Regidium\CommonBundle\Document\Agent $agent)
    {
        $this->agents->removeElement($agent);
    }

    /**
     * Get agents
     *
     * @return \Doctrine\Common\Collections\Collection $agents
     */
    public function getAgents()
    {
        return $this->agents;
    }

    /**
     * Add user
     *
     * @param Regidium\CommonBundle\Document\User $user
     */
    public function addUser(\Regidium\CommonBundle\Document\User $user)
    {
        $this->users[] = $user;
    }

    /**
     * Remove user
     *
     * @param Regidium\CommonBundle\Document\User $user
     */
    public function removeUser(\Regidium\CommonBundle\Document\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection $users
     */
    public function getUsers()
    {
        return $this->users;
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
     * @return \Doctrine\Common\Collections\Collection $chats
     */
    public function getChats()
    {
        return $this->chats;
    }

    /**
     * Set settings
     *
     * @param array $settings
     * @return self
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * Get settings
     *
     * @return array $settings
     */
    public function getSettings()
    {
        return $this->settings;
    }
}
