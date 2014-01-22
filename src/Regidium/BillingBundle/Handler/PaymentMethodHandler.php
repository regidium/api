<?php

namespace Regidium\BillingBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\BillingBundle\Document\PaymentMethod;

class PaymentMethodHandler extends AbstractHandler implements PaymentMethodHandlerInterface
{
    /**
     * Get one billing payment method by criteria.
     *
     * @param array $criteria
     *
     * @return PaymentMethod
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get billing payment methods by criteria.
     *
     * @param array $criteria
     *
     * @return PaymentMethod
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Get all billing payment methods.
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
}