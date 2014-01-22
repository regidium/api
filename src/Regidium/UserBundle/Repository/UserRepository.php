<?php

namespace Regidium\UserBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class UserRepository extends DocumentRepository
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
        return count($this->findBy($criteria));
    }

}