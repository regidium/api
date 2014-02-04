<?php

namespace Regidium\UserBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\CommonBundle\Form\PersonForm;
use Regidium\UserBundle\Form\UserForm;
use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\User;
use Regidium\CommonBundle\Document\Widget;

class UserHandler extends AbstractHandler
{
    /**
     * Get one user by criteria.
     *
     * @param array $criteria
     *
     * @return User
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get users by criteria.
     *
     * @param array $criteria
     *
     * @return User
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Get a list of users.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Get a list of agent by criteria.
     *
     * @param array $criteria
     * @param int   $limit    limit of the result
     * @param int   $offset   starting from the offset
     *
     * @return array
     */
    public function allAgents($criteria = array(), $limit = 5, $offset = 0)
    {
        return $this->repository->findBy($criteria, null, $limit, $offset);
    }

    /**
     * Get a list of users by criteria.
     *
     * @param array $criteria
     * @param int   $limit    limit of the result
     * @param int   $offset   starting from the offset
     *
     * @return array
     */
    public function allUsers($criteria = array(), $limit = 5, $offset = 0)
    {
        return $this->repository->findBy($criteria, null, $limit, $offset);
    }

    /**
     * Create a new user.
     *
     * @param Widget $widget
     * @param array  $parameters
     *
     * @return User
     */
    public function post(Widget $widget, array $parameters)
    {
        $user = $this->createEntity();
        $person = new Person();

        return $this->processForm($user, $person, $widget, $parameters, 'POST');
    }

    /**
     * Edit a user.
     *
     * @param User  $user
     * @param array $parameters
     *
     * @return User
     */
    public function put(User $user, array $parameters)
    {
        $person = $user->getPerson();
        $widget = $user->getWidget();

        return $this->processForm($user, $person, $widget, $parameters, 'PUT');
    }

    /**
     * Remove exist User
     *
     * @param string $criteria
     *
     * @return bool|int
     */
    public function delete($criteria) {
        $user = $this->one($criteria);
        if (!$user instanceof User) {
            return 404;
        }

        try {
            $this->dm->remove($user);
            $this->dm->flush();
            return 200;
        } catch (\Exception $e) {
            return 500;
        }
    }

    /**
     * Save edit User
     *
     * @param User  $user
     *
     * @return User
     */
    public function edit(User $user) {
        $this->dm->flush($user);
        return $user;
    }

    /**
     *
     * Find user by external service id
     *
     * @param string $provider External service provider
     * @param int    $id       External service user id
     *
     * @return User|null
     */
    public function oneByExternalService($provider, $id) {
        return $this->repository
            ->field("external_service.{$provider}.data.id")->equals($id)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * Processes the form.
     *
     * @param User   $user
     * @param Person $person
     * @param Widget $widget
     * @param array  $parameters
     * @param string $method
     *
     * @return string|array|Person
     *
     */
    private function processForm(User $user, Person $person, Widget $widget, array $parameters, $method = 'PUT')
    {
        $userParameters = [
            'status' => isset($parameters['status']) ? $parameters['status'] : User::STATUS_DEFAULT
        ];

        $formUser = $this->formFactory->create(new UserForm(), $user, array('method' => $method));
        $formUser->submit($userParameters, 'PATCH' !== $method);
        if ($formUser->isValid()) {
            $user = $formUser->getData();
            if (!$user instanceof User) {
                return 'Server error';
            }

            $user->setWidget($widget);

            $personParameters = [
                'fullname' => isset($parameters['fullname']) ? $parameters['fullname'] : '',
                'avatar' => isset($parameters['avatar']) ? $parameters['avatar'] : '',
                'email' => isset($parameters['email']) ? $parameters['email'] : '',
                'password' => isset($parameters['password']) ? $parameters['password'] : '',
                'status' => isset($parameters['status']) ? $parameters['status'] : '',
                'country' => isset($parameters['country']) ? $parameters['country'] : '',
                'city' => isset($parameters['city']) ? $parameters['city'] : '',
                'ip' => isset($parameters['ip']) ? $parameters['ip'] : '',
                'device' => isset($parameters['device']) ? $parameters['device'] : '',
                'os' => isset($parameters['os']) ? $parameters['os'] : '',
                'browser' => isset($parameters['browser']) ? $parameters['browser'] : '',
                'keyword' => isset($parameters['keyword']) ? $parameters['keyword'] : '',
                'language' => isset($parameters['language']) ? $parameters['language'] : ''
            ];

            $formPerson = $this->formFactory->create(new PersonForm([ 'email_exclusion' => $person->getEmail() ]), $person, array('method' => $method));
            $formPerson->submit($personParameters, 'PATCH' !== $method);
            if ($formPerson->isValid()) {
                /** @var Person $person */
                $person = $formPerson->getData();
                $person->setUser($user);

                $this->dm->persist($person);
                $this->dm->persist($user);

                $this->dm->flush();

                return $person;
            }

            return $this->getFormErrors($formPerson);
        }

        return $this->getFormErrors($formUser);
    }
}