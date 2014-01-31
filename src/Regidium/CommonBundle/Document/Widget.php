<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

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
    protected $id;

    /**
     * @MongoDB\String
     * @MongoDB\UniqueIndex(safe="true")
     */
    protected $uid;

    /**
     * @MongoDB\String
     */
    private $model_type;

    /**
     * @MongoDB\String
     */
    protected $css;

    /**
     * @MongoDB\Hash
     */
    protected $option;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Client", mappedBy="widget")
     */
    protected $client;

    /* =============== General =============== */

    public function __construct()
    {
        $this->setUid(uniqid());

        $this->setModelType('widget');
    }

    public function __toString()
    {
        return $this->css;
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
     * Set option
     *
     * @param array $option
     * @return self
     */
    public function setOption($option)
    {
        $this->option = $option;
        return $this;
    }

    /**
     * Get option
     *
     * @return array $option
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * Set client
     *
     * @param Client $client
     * @return self
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get client
     *
     * @return Client $client
     */
    public function getClient()
    {
        return $this->client;
    }
}
