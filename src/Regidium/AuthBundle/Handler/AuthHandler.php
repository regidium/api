<?php

namespace Regidium\AuthBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\CommonBundle\Document\Auth;
use Regidium\CommonBundle\Document\Person;

class AuthHandler extends AbstractHandler
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
     * @param Person $person
     * @param array $parameters
     *
     * @return Auth
     */
    public function post($person, array $parameters)
    {
        /** @var \Regidium\CommonBundle\Document\Auth $auth */
        $auth = $this->createEntity();
        $auth->setPerson($person);

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
     * @param Person $person
     *
     * @return bool
     */
    public function close($person)
    {
        /** @var \Regidium\CommonBundle\Document\Auth[] $auths */
        $auths = $person->getAuths();
        foreach($auths as $auth) {
            $auth->setEnded(time());
        }

        $this->dm->flush();
        return true;
    }
}