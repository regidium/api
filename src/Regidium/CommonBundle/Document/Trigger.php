<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\TriggerRepository",
 *      collection="triggers",
 *      requireIndexes=false
 *  )
 */
class Trigger
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
     * @MongoDB\Int
     */
    private $priority;

    /**
     * @MongoDB\Int
     */
    private $event;

    /**
     * @MongoDB\String
     */
    private $event_params;

    /**
     * @MongoDB\Int
     */
    private $result;

    /**
     * @MongoDB\String
     */
    private $result_params;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Widget", cascade={"persist", "merge", "detach"}, inversedBy="triggers")
     */
    protected $widget;

    /* =============== Constants =============== */

    const EVENT_WIDGET_CREATED = 1;
    const EVENT_WORD_SEND = 2;
    const EVENT_TIME_ONE_PAGE = 3;
    const EVENT_VISIT_PAGE = 4;
    const EVENT_VISIT_FROM_URL = 5;
    const EVENT_VISIT_FROM_KEY_WORD = 6;

    static public function getEvents()
    {
        return [
            self::EVENT_WIDGET_CREATED,
            self::EVENT_WORD_SEND,
            self::EVENT_TIME_ONE_PAGE,
            self::EVENT_VISIT_PAGE,
            self::EVENT_VISIT_FROM_URL,
            self::EVENT_VISIT_FROM_KEY_WORD
        ];
    }

    const RESULT_MESSAGE_SEND = 1;
    const RESULT_OPERATORS_ALERT = 2;
    const RESULT_WIDGET_OPEN = 3;
    const RESULT_WIDGET_BELL = 4;

    static public function getResults()
    {
        return [
            self::RESULT_MESSAGE_SEND,
            self::RESULT_OPERATORS_ALERT,
            self::RESULT_WIDGET_OPEN,
            self::RESULT_WIDGET_BELL
        ];
    }


    /* ============= COMMON ============= */

    public function __construct()
    {
        $this->setUid(uniqid());
    }

    public function toArray(array $options = [])
    {
        $return = [
            'uid' => $this->uid,
            'name' => $this->name,
            'event' => $this->event,
            'event_params' => $this->event_params,
            'result' => $this->result,
            'result_params' => $this->result_params
        ];

        if (in_array('widget', $options) && $this->widget) {
            $return['widget'] = $this->widget->toArray();
        }

        return $return;
    }

    /* ============= GET/SET ============= */

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
     * Set event
     *
     * @param int $event
     * @return self
     */
    public function setEvent($event)
    {
        $this->event = $event;
        return $this;
    }

    /**
     * Get event
     *
     * @return int $event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set eventParams
     *
     * @param string $eventParams
     * @return self
     */
    public function setEventParams($eventParams)
    {
        $this->event_params = $eventParams;
        return $this;
    }

    /**
     * Get eventParams
     *
     * @return string $eventParams
     */
    public function getEventParams()
    {
        return $this->event_params;
    }

    /**
     * Set result
     *
     * @param int $result
     * @return self
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Get result
     *
     * @return int $result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set resultParams
     *
     * @param string $resultParams
     * @return self
     */
    public function setResultParams($resultParams)
    {
        $this->result_params = $resultParams;
        return $this;
    }

    /**
     * Get resultParams
     *
     * @return string $resultParams
     */
    public function getResultParams()
    {
        return $this->result_params;
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
     * Set priority
     *
     * @param int $priority
     * @return self
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Get priority
     *
     * @return int $priority
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
