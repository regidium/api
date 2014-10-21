<?php
/**
 * @author Russell Kvashnin <russell.kvashnin@gmail.com>
 */

namespace Regidium\CommonBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class ResetPasswordRepository extends DocumentRepository
{
    /**
     * @param $agent
     * @return array|null|object
     */
    public function findByAgent($agent)
    {
        $qb = $this->createQueryBuilder('ResetPasswordRequest');

        $qb
            ->field('agent')->references($agent)
        ;

        return $qb->getQuery()->getSingleResult();
    }
}