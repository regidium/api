<?php

namespace Regidium\CommonBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class AbstractRepository extends DocumentRepository
{
    /**
     * Find count documents in the repository.
     *
     * @param array $criteria Query criteria
     *
     * @return int The count
     */
    public function count(array $criteria = [])
    {
        return count($this->findBy($criteria));
    }

}