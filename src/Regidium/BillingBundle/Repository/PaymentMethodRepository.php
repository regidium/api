<?php

namespace Regidium\BillingBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class PaymentMethodRepository extends DocumentRepository
{
    /**
     * Find count documents in the repository.
     *
     * @param array $criteria Query criteria
     *
     * @return int The count
     */
    public function count(array $criteria = array())
    {
        return $this->findBy($criteria)->count(true);
    }

}