<?php

namespace Regidium\ChatBundle\Handler;

use Regidium\ChatBundle\Form\ChatForm;
use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\CommonBundle\Document\Agent;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\User;
use Regidium\CommonBundle\Document\Widget;

class ChatHandler extends AbstractHandler
{
    /**
     * Получение одного чата по условию.
     *
     * @param array $criteria
     *
     * @return Chat
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Получение чатов по условию.
     *
     * @param array $criteria
     *
     * @return Chat[]
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Создание новой сущности
     *
     * @param array $data
     *
     * @return object
     */
    public function post(array $data)
    {
        $entity = $this->createEntity();

        return $this->processForm($entity, $data, 'POST');
    }

    /**
     * Disconnect Chat
     *
     * @param Chat $chat
     *
     * @return Chat
     */
    public function offline(Chat $chat) {
        $chat->setStatus(Chat::STATUS_OFFLINE);
        $this->edit($chat);
        return $chat;
    }

    /**
     * @todo убрать
     *
     * Save edited Chat
     *
     * @param Chat  $chat
     *
     * @return Chat
     */
    public function edit(Chat $chat) {
        $this->dm->persist($chat);
        $this->dm->flush($chat);
        return $chat;
    }

    /**
     * Обработка формы.
     *
     * @param Chat   $chat
     * @param array  $parameters
     * @param string $method
     *
     * @return array|Chat
     *
     */
    public function processForm(Chat $chat, array $parameters, $method = 'PUT')
    {
        $form = $this->formFactory->create(new ChatForm(), $chat, ['method' => $method]);
        $form->submit($parameters, 'PATCH' !== $method);
        //var_dump($parameters);die();
        if ($form->isValid()) {
            /** @var Chat $chat */
            $chat = $form->getData();
            if (!$chat instanceof Chat) {
                return 'Server error';
            }

            $widget = $this->dm->getRepository('Regidium\CommonBundle\Document\Widget')->findOneBy(['uid' => $form->get('widget_uid')->getData()]);
            $chat->setWidget($widget);

            $user = $this->dm->getRepository('Regidium\CommonBundle\Document\User')->findOneBy(['uid' => $form->get('user_uid')->getData()]);
            $chat->setUser($user);

            $operator = $this->dm->getRepository('Regidium\CommonBundle\Document\Agent')->findOneBy(['uid' => $form->get('operator_uid')->getData()]);
            if ($operator instanceof Agent) {
                $chat->setOperator($operator);
            }

            $this->dm->persist($chat);
            $this->dm->flush($chat);

            return $chat;
        }

        return $this->getFormErrors($form);
    }
}