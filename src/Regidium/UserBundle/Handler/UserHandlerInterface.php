<?php

namespace Regidium\UserBundle\Handler;

use Regidium\UserBundle\Document\User;

interface UserHandlerInterface
{
    /**
     * Get a User given the identifier
     *
     * @api
     *
     * @param int $id
     *
     * @return array
     */
    public function get($id);

    /**
     * Get list of users
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post User, creates a new User.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return User
     */
    public function post(array $parameters);

    /**
     * Edit a User.
     *
     * @api
     *
     * @param User  $user
     * @param array $parameters
     *
     * @return User
     */
    public function put(User $user, array $parameters);

    /**
     * Partially update a User.
     *
     * @api
     *
     * @param User  $user
     * @param array $parameters
     *
     * @return User
     */
    public function patch(User $user, array $parameters);
}