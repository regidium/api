<?php

namespace Regidium\ChatBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\ChatMessage;
use Regidium\CommonBundle\Document\User;
use Regidium\CommonBundle\Document\Agent;

class ChatMessageHandler extends AbstractHandler
{
    /**
     * Create a new chat message.
     *
     * @param Chat $chat
     * @param User|Agent $sender
     * @param User|Agent|null $receiver
     * @param array $text
     *
     * @return ChatMessage
     */
    public function post(Chat $chat, $sender, $receiver = null, $text)
    {
        $chat_message = $this->createChatMessage();

        $chat_message->setChat($chat);
        $chat_message->setText($text);
        $chat_message->setSender($sender);
        if ($receiver) {
            $chat_message->setReceiver($receiver);
        }

        $this->dm->persist($chat_message);
        $this->dm->flush($chat_message);
        return $chat_message;
    }

    private function createChatMessage()
    {
        return new $this->entityClass();
    }
}