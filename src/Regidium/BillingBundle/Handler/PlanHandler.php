<?php

namespace Regidium\BillingBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\BillingBundle\Document\Plan;

class PlanHandler extends AbstractHandler
{
    /**
     * Get one billing plan by criteria.
     *
     * @param array $criteria
     *
     * @return Plan
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get billing plans by criteria.
     *
     * @param array $criteria
     *
     * @return Plan
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Get all billing plans.
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