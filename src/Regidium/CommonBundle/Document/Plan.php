<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

use Regidium\CommonBundle\Document\Auth;
use Regidium\CommonBundle\Document\Chat;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\PlanRepository",
 *      collection="plans",
 *      requireIndexes=false
 *  )
 */
class Plan
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
     * @MongoDB\Int
     */
    private $type;

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\Widget", mappedBy="plan")
     */
    private $widgets;

    /* =============== Constants =============== */

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->widgets = new ArrayCollection();

        $this->setModelType('plan');
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
     * Add widget
     *
     * @param Regidium\CommonBundle\Document\Widget $widget
     */
    public function addWidget(\Regidium\CommonBundle\Document\Widget $widget)
    {
        $this->widgets[] = $widget;
    }

    /**
     * Remove widget
     *
     * @param Regidium\CommonBundle\Document\Widget $widget
     */
    public function removeWidget(\Regidium\CommonBundle\Document\Widget $widget)
    {
        $this->widgets->removeElement($widget);
    }

    /**
     * Get widgets
     *
     * @return Doctrine\Common\Collections\Collection $widgets
     */
    public function getWidgets()
    {
        return $this->widgets;
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
}