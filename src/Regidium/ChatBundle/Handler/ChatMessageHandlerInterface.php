<?php

namespace Regidium\ChatBundle\Handler;

use Regidium\ChatBundle\Document\Chat;
use Regidium\ChatBundle\Document\ChatMessage;

use Regidium\UserBundle\Document\Agent;
use Regidium\UserBundle\Document\User;

/** @todo Привести в порядок */
interface ChatMessageHandlerInterface
{
    /**
     * Create chat message.
     *
     * @api
     *
     * @param Chat $chat
     * @param User|Agent $sender
     * @param User|Agent|null $receiver
     * @param string $text
     *
     * @return ChatMessage
     */
    public function post(Chat $chat, $sender, $receiver = null, $text);
}