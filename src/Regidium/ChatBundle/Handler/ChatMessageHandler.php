<?php

namespace Regidium\ChatBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\ChatMessage;
use Regidium\CommonBundle\Document\Person;

class ChatMessageHandler extends AbstractHandler
{
    /**
     * Create a new chat message.
     *
     * @param Chat   $chat
     * @param Person $sender
     * @param Person $receiver
     * @param string  $text
     *
     * @return ChatMessage
     */
    public function post(Chat $chat, Person $sender, $receiver = null, $text)
    {
        /** @var ChatMessage $chat_message */
        $chat_message = $this->createEntity();

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
}