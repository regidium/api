<?php

namespace Regidium\AuthBundle\Handler;

use Regidium\AuthBundle\Document\Auth;
use Regidium\UserBundle\Document\User;
use Regidium\AgentBundle\Document\Agent;

interface AuthHandlerInterface
{
    /**
     * Get a Auth by criteria
     *
     * @api
     *
     * @param array $criteria
     *
     * @return array
     */
    public function get(array $criteria);

    /**
     * Creates a new Auth.
     *
     * @api
     *
     * @param User|Agent $owner
     * @param array $parameters
     *
     * @return Auth
     */
    public function post($owner, array $parameters);

    /**
     * Edit a Auth.
     *
     * @api
     *
     * @param Auth  $auth
     * @param array $parameters
     *
     * @return Auth
     */
    public function put(Auth $auth, array $parameters);
}