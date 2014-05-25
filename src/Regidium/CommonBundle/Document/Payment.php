<?php

namespace Regidium\CommonBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(
 *      repositoryClass="Regidium\CommonBundle\Repository\PaymentRepository",
 *      collection="payments",
 *      requireIndexes=false
 *  )
 */
class Payment
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
     * @MongoDB\Float
     */
    private $amount;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\PaymentMethod")
     */
    private $payment_method;

    /**
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="Regidium\CommonBundle\Document\Widget", cascade={"persist", "merge", "detach"}, inversedBy="payments")
     */
    private $widget;

    /* ============= COMMON ============= */

    public function __construct()
    {
        $this->setUid(uniqid());
    }

    public function toArray(array $options = [])
    {
        $return = [
            'uid' => $this->uid,
            'amount' => $this->amount
        ];

        if (isset($options['payment_method'])) {
            $return['payment_method'] = $this->payment_method->toArray();
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
