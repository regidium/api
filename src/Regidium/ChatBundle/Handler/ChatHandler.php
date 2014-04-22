<?php

namespace Regidium\ChatBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;

use Regidium\ChatBundle\Form\ChatForm;
use Regidium\ChatBundle\Form\UserForm;
use Regidium\CommonBundle\Document\Agent;
use Regidium\CommonBundle\Document\User;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\ChatMessage;
use Regidium\CommonBundle\Document\Widget;

class ChatHandler extends AbstractHandler
{
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

        $user = new User();
        $user_form = $this->formFactory->create(new UserForm(), $user, ['method' => 'POST']);
        $user_form->submit($data['user'], false);
        $user = $user_form->getData();
        $data['user'] = $user;

        /** @todo не присылать */
        unset($data['messages']);

        $form = $this->formFactory->create(new ChatForm(), $entity, ['method' => 'POST']);
        $form->submit($data, false);

        if ($form->isValid()) {
            /** @var Chat $chat */
            $chat = $form->getData();
            if (!$chat instanceof Chat) {
                return 'Server error';
            }

            $widget = $this->dm->getRepository('Regidium\CommonBundle\Document\Widget')->findOneBy(['uid' => $form->get('widget_uid')->getData()]);
            $chat->setWidget($widget);

            $this->dm->persist($chat);
            $this->dm->flush($chat);

            return $chat;
        }

        return $this->getFormErrors($form);
    }

    /**
     * Подключение чата
     *
     * @param Chat $chat
     *
     * @return Chat
     */
    public function online(Chat $chat) {
        $chat->setOldStatus($chat->getStatus());
        $chat->setStatus(Chat::STATUS_ONLINE);
        $chat->setEndedAt(null);
        $this->edit($chat);

        return $chat;
    }

    /**
     * Общение в чате
     *
     * @param Chat $chat
     *
     * @return Chat
     */
    public function chatting(Chat $chat) {
        $chat->setOldStatus($chat->getStatus());
        $chat->setStatus(Chat::STATUS_CHATTING);
        $chat->setEndedAt(null);
        $this->edit($chat);

        return $chat;
    }

    /**
     * Отключение чата
     *
     * @param Chat $chat
     *
     * @return Chat
     */
    public function offline(Chat $chat) {
        $chat->setOldStatus($chat->getStatus());
        $chat->setStatus(Chat::STATUS_OFFLINE);
        $chat->setEndedAt(time());
        $this->edit($chat);

        return $chat;
    }


    /**
     * Авторизационные данные пользователя
     *
     * @param Chat $chat
     * @param array $data
     *
     * @return Chat
     */
    public function auth(Chat $chat, $data) {
        $user = $chat->getUser();
        $user->setFirstName($data['first_name']);
        $user->setEmail($data['email']);

        $chat->setUser($user);
        $this->edit($chat);

        return $chat;
    }

    /**
     * Агент подключился
     *
     * @param Chat $chat
     * @param Agent $agent
     *
     * @return Chat
     */
    public function agentEnter(Chat $chat, Agent $agent) {
        $chat->setAgent($agent);
        $chat->setStatus(Chat::STATUS_CHATTING);

        // Прочитываем все сообщения чата
        /** @var ChatMessage[] $messages  */
        $messages = $chat->getMessages();
        foreach($messages as $message) {
            $message->setReaded(true);
            $this->dm->persist($message);
        }

        $this->dm->persist($chat);
        $this->dm->flush();

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
}