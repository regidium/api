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
     * @param array $chats_uids
     */
    public function disconnect(array $chats_uids = [])
    {
        $this->dm->createQueryBuilder('Regidium\CommonBundle\Document\Chat')
            ->update()
            ->multiple(true)
            ->field('ended_at')->set(time())
            ->field('status')->set(Chat::STATUS_OFFLINE)
            ->field('uid')->notIn($chats_uids)
            ->field('status')->notEqual(Chat::STATUS_OFFLINE)
            ->getQuery()
            ->execute()
        ;
    }
}