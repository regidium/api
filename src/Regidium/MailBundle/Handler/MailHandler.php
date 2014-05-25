<?php

namespace Regidium\MailBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\CommonBundle\Document\Mail;

class MailHandler extends AbstractHandler
{
    /**
     * Получение письма по критерию.
     *
     * @param array $criteria Критерий
     *
     * @return Mail
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Получение списка писем по критерю.
     *
     * @param array $criteria Критерий
     *
     * @return Mail
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Получение всех писем.
     *
     * @param int $limit  колчество результатов
     * @param int $offset смещение списка
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy([], null, $limit, $offset);
    }

    /**
     * Создание нового письма
     *
     * @param array $data
     *
     * @return Mail
     */
    public function post(array $data)
    {
        /** @var Mail $mail */
        $mail = $this->createEntity();

        $mail->setSenderEmail('robot@regidium.com');
        $mail->setReceiverEmails($data['receivers']);
        $mail->setTitle($data['title']);
        $mail->setTemplate($data['template']);

        if (isset($data['data'])) {
            $mail->setData($data['data']);
        }

        $this->dm->persist($mail);
        $this->dm->flush($mail);

        return $mail;
    }
}