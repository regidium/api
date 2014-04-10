<?php

namespace Regidium\ServiceBundle\Handler;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;

use Regidium\CommonBundle\Document\Chat;

class ServiceHandler
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    public function __construct(ManagerRegistry $mr)
    {
        $this->dm = $mr->getManager();
    }

    /**
     * Отключение повисших пользователей (после перезагрузки)
     *
     * @param array $socket_ids
     */
    public function disconnect(array $socket_ids = [])
    {
        $this->dm->createQueryBuilder('Regidium\CommonBundle\Document\Chat')
            ->update()
            ->multiple(true)
            ->field('status')->set(Chat::STATUS_OFFLINE)
            ->field('socket_id')->notIn($socket_ids)
            ->getQuery()
            ->execute()
        ;
    }
}