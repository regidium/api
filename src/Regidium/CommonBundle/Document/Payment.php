<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\Client;
use Regidium\CommonBundle\Document\Plan;
use Regidium\CommonBundle\Document\PaymentMethod;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\PaymentRepository",
 *      collection="payments",
 *      requireIndexes=false
 *  )
 */
class Payment
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
     * @MongoDB\Float
     */
    private $amount;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Client", cascade={"all"}, inversedBy="payments")
     */
    private $client;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\PaymentMethod")
     */
    private $payment_method;

    /* ============= COMMON ============= */

    public function __construct()
    {
        $this->setUid(uniqid());

        $this->setModelType('payment');
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
     * Set amount
     *
     * @param float $amount
     * @return self
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get amount
     *
     * @return float $amount
     */
    public function getAmount()
    {
        return $this->amount;
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
     * Set paymentMethod
     *
     * @param Regidium\CommonBundle\Document\PaymentMethod $paymentMethod
     * @return self
     */
    public function setPaymentMethod(\Regidium\CommonBundle\Document\PaymentMethod $paymentMethod)
    {
        $this->payment_method = $paymentMethod;
        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return Regidium\CommonBundle\Document\PaymentMethod $paymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
    }
}
