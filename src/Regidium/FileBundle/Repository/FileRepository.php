<?php

namespace Regidium\FileBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;


/** @todo СДелать абстракцию репозитариев */
class FileRepository extends DocumentRepository
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