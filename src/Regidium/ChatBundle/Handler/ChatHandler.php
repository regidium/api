<?php

namespace Regidium\ChatBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\User;
use Regidium\CommonBundle\Document\Widget;

class ChatHandler extends AbstractHandler
{
    /**
     * Get one chat by criteria.
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
     * Get chats by criteria.
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
     * Create a new chat.
     *
     * @param Widget $widget
     * @param User   $user
     *
     * @return Chat
     */
    public function post(Widget $widget, User $user)
    {
        /** @var Chat $chat */
        $chat = $this->createEntity();

        $chat->setWidget($widget);
        $chat->setUser($user);

        $this->dm->persist($chat);
        $this->dm->flush();

        return $chat;
    }

    /**
     * Edit a chat.
     *
     * @todo Проверить необходимость
     *
     * @param Chat  $chat
     * @param array $parameters
     *
     * @return Chat
     */
    public function put(Chat $chat, array $parameters)
    {
        return $chat;
        //return $this->processForm($chat, $parameters, 'PUT');
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
        $this->dm->flush($chat);
        return $chat;
    }
}