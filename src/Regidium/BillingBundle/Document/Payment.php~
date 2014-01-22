<?php

namespace Regidium\BillingBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

use Regidium\CommonBundle\Document\Interfaces\IdInterface;
use Regidium\CommonBundle\Document\Interfaces\UidInterface;

use Regidium\ClientBundle\Document\Client;
use Regidium\BillingBundle\Document\Plan;
use Regidium\BillingBundle\Document\PaymentMethod;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\BillingBundle\Repository\PaymentRepository",
 *      collection="payments",
 *      requireIndexes=false
 *  )
 */
class Payment implements IdInterface, UidInterface
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
     * @MongoDB\Float
     */
    private $amount;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\ClientBundle\Document\Client", cascade={"all"}, inversedBy="payments")
     */
    private $client;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\BillingBundle\Document\PaymentMethod")
     */
    private $payment_method;

    /**
     * @MongoDB\String
     */
    private $model_type;

    public function __construct()
    {
        $this->setUid(uniqid());
        $this->setModelType('payment');
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
     * @param Regidium\ClientBundle\Document\Client $client
     * @return self
     */
    public function setClient(\Regidium\ClientBundle\Document\Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get client
     *
     * @return Regidium\ClientBundle\Document\Client $client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set paymentMethod
     *
     * @param Regidium\BillingBundle\Document\PaymentMethod $paymentMethod
     * @return self
     */
    public function setPaymentMethod(\Regidium\BillingBundle\Document\PaymentMethod $paymentMethod)
    {
        $this->payment_method = $paymentMethod;
        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return Regidium\BillingBundle\Document\PaymentMethod $paymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
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
}
