<?php

namespace Regidium\BillingBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;

use Regidium\ClientBundle\Document\Client;
use Regidium\BillingBundle\Document\Payment;
use Regidium\BillingBundle\Document\PaymentMethod;

class PaymentHandler extends AbstractHandler implements PlanHandlerInterface
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
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Create a new payment.
     *
     * @param Client $client
     * @param PaymentMethod $payment_method
     * @param array $parameters
     *
     * @return Payment
     */
    public function post(Client $client, PaymentMethod $payment_method, $amount)
    {
        $entity = $this->createEntity();
        $entity->setClient($client);
        $entity->setPaymentMethod($payment_method);
        $entity->setAmount($amount);

        $this->dm->persist($entity);
        $this->dm->flush($entity);

        return $entity;
    }
}