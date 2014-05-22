<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

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
    private $name;

    /**
     * @MongoDB\Float
     */
    private $cost;

    /**
     * @MongoDB\Int
     */
    private $count_agents;

    /**
     * @MongoDB\Int
     */
    private $type;

    /* =============== References =============== */

    /**
     * @MongoDB\ReferenceMany(targetDocument="Regidium\CommonBundle\Document\Widget", mappedBy="plan")
     */
    private $widgets;

    /* =============== Constants =============== */

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->widgets = new ArrayCollection();
    }

    public function toArray()
    {
        $return = [
            'uid' => $this->uid,
            'name' => $this->name,
            'cost' => $this->cost,
            'count_agents' => $this->count_agents,
            'type' => $this->type
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
