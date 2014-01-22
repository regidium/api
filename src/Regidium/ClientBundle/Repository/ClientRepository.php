<?php

namespace Regidium\ClientBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class ClientRepository extends DocumentRepository
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