<?php

namespace Regidium\AuthBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\AuthBundle\Document\Auth;
use Regidium\UserBundle\Document\User;
use Regidium\AgentBundle\Document\Agent;

class AuthHandler extends AbstractHandler implements AuthHandlerInterface
{

    /**
     * Get one auth by criteria.
     *
     * @param array $criteria
     *
     * @return Auth
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get a auth.
     *
     * @param array $criteria
     *
     * @return Auth
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Create a new auth.
     *
     * @param User|Agent $owner
     * @param array $parameters
     *
     * @return Auth
     */
    public function post($owner, array $parameters)
    {
        $auth = $this->createAuth();
        $auth->setOwner($owner);

        if (isset($parameters['remember']) && $parameters['remember']) {
            $auth->setRemember(true);
        }

        $this->dm->persist($auth);
        $this->dm->flush($auth);
        return $auth;
    }

    /**
     * Edit auth.
     *
     * @param Auth  $auth
     * @param array $parameters
     *
     * @return Auth
     */
    public function put(Auth $auth, array $parameters)
    {
        $auth->setToken(uniqid('r', true));
        if (isset($parameters['remember']) && $parameters['remember']) {
            $auth->setRemember(true);
        }

        $this->dm->flush($auth);
        return $auth;
    }

    /**
     * Close auth session.
     *
     * @param User|Agent $object
     *
     * @return bool
     */
    public function close($object)
    {
        $auths = $object->getAuths();
        foreach($auths as $auth) {
            $auth->setEnded(time());
        }

        $this->dm->flush();
        return true;
    }

    private function createAuth()
    {
        return new $this->entityClass();
    }
}