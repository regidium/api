<?php

namespace Regidium\BillingBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\Interfaces\IdInterface;
use Regidium\CommonBundle\Document\Interfaces\UidInterface;
use Regidium\CommonBundle\Document\Interfaces\StatusInterface;

use Regidium\AuthBundle\Document\Auth;
use Regidium\ChatBundle\Document\Chat;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\BillingBundle\Repository\PlanRepository",
 *      collection="plans",
 *      requireIndexes=false
 *  )
 */
class Plan implements IdInterface, UidInterface
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
    private $name;

    /**
     * @MongoDB\Float
     */
    private $cost;

    /**
     * @MongoDB\Int
     */
    private $count_chats;

    /**
     * @MongoDB\Int
     */
    private $count_agents;

    /**
     * @MongoDB\String
     */
    private $model_type;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\ClientBundle\Document\Client", mappedBy="plan")
     */
    protected $clients;

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setModelType('plan');
    }

    /**
     * Set id
     *
     * @param int $id
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
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set cost
     *
     * @param float $cost
     * @return self
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * Get cost
     *
     * @return float $cost
     */
    public function getCost()
    {
        return $this->cost;
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
     * Set countChats
     *
     * @param int $countChats
     * @return self
     */
    public function setCountChats($countChats)
    {
        $this->count_chats = $countChats;
        return $this;
    }

    /**
     * Get countChats
     *
     * @return int $countChats
     */
    public function getCountChats()
    {
        return $this->count_chats;
    }

    /**
     * Set countAgents
     *
     * @param int $countAgents
     * @return self
     */
    public function setCountAgents($countAgents)
    {
        $this->count_agents = $countAgents;
        return $this;
    }

    /**
     * Get countAgents
     *
     * @return int $countAgents
     */
    public function getCountAgents()
    {
        return $this->count_agents;
    }

    /**
     * Add client
     *
     * @param Regidium\ClientBundle\Document\Client $client
     */
    public function addClient(\Regidium\ClientBundle\Document\Client $client)
    {
        $this->clients[] = $client;
    }

    /**
     * Remove client
     *
     * @param Regidium\ClientBundle\Document\Client $client
     */
    public function removeClient(\Regidium\ClientBundle\Document\Client $client)
    {
        $this->clients->removeElement($client);
    }

    /**
     * Get clients
     *
     * @return Doctrine\Common\Collections\Collection $clients
     */
    public function getClients()
    {
        return $this->clients;
    }
}
