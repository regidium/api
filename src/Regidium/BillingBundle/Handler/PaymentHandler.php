<?php

namespace Regidium\BillingBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;

use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Payment;
use Regidium\CommonBundle\Document\PaymentMethod;

class PaymentHandler extends AbstractHandler
{
    /**
     * Get one payment by criteria.
     *
     * @param array $criteria
     *
     * @return Payment
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get payments by criteria.
     *
     * @param array $criteria
     *
     * @return Payment
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Get all payments.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy([], null, $limit, $offset);
    }

    /**
     * Create a new payment.
     *
     * @param Widget        $widget
     * @param PaymentMethod $payment_method
     * @param float         $amount
     *
     * @return Payment
     */
    public function post(Widget $widget, PaymentMethod $payment_method, $amount)
    {
        /** @var Payment $entity */
        $entity = $this->createEntity();
        $entity->setWidget($widget);
        $entity->setPaymentMethod($payment_method);
        $entity->setAmount($amount);

        $this->dm->persist($entity);
        $this->dm->flush($entity);

        return $entity;
    }
}