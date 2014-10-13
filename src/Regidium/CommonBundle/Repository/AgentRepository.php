<?php

namespace Regidium\CommonBundle\Repository;

class AgentRepository extends AbstractRepository
{

    /**
     * @param $widget
     * @return array|null|object
     */
    public function findOneByBusyness($widget)
    {
        $qb = $this->createQueryBuilder('Agent');

        $qb
            ->field('widget')->references($widget)
            ->sort('busyness','asc')
        ;

        return $qb->getQuery()->getSingleResult();
    }
}