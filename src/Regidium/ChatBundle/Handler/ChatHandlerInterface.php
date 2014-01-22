<?php

namespace Regidium\ChatBundle\Handler;

use Regidium\ChatBundle\Document\Chat;
use Regidium\UserBundle\Document\User;
use Regidium\ClientBundle\Document\Client;

/** @todo Привести в порядок */
interface ChatHandlerInterface
{
    /**
     * Get a Chat given the uid
     *
     * @api
     *
     * @param array $criteria
     *
     * @return array
     */
    public function get(array $criteria);

    /**
     * Post Chat, creates a new Chat.
     *
     * @api
     *
     * @param Client $client
     * @param User $user
     * @param array $parameters
     *
     * @return Chat
     */
    public function post(Client $client, User $user, array $parameters);

    /**
     * Edit a Chat.
     *
     * @api
     *
     * @param Chat  $chat
     * @param array $parameters
     *
     * @return Chat
     */
    public function put(Chat $chat, array $parameters);
}