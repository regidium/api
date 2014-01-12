<?php

namespace Regidium\UserBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\UserBundle\Form\UserForm;
use Regidium\UserBundle\Document\User;

class UserHandler extends AbstractHandler implements UserHandlerInterface
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
     * Create a new user.
     *
     * @param array $parameters
     *
     * @return User
     */
    public function post(array $parameters)
    {
        $user = $this->createUser();

        return $this->processForm($user, $parameters, 'POST');
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
        return $this->processForm($user, $parameters, 'PUT');
    }

    /**
     * Partially update a user.
     *
     * @param User  $user
     * @param array $parameters
     *
     * @return User
     */
    public function patch(User $user, array $parameters)
    {
        return $this->processForm($user, $parameters, 'PATCH');
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
        return $this->dm->createQueryBuilder('Regidium\UserBundle\Document\User')
            ->field("external_service.{$provider}.data.id")->equals($id)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * Processes the form.
     *
     * @param User   $user
     * @param array  $parameters
     * @param string $method
     *
     * @return User|\Symfony\Component\Form\FormError[]
     *
     */
    private function processForm(User $user, array $parameters, $method = 'PUT')
    {
        $form = $this->formFactory->create(new UserForm([ 'email_exclusion' => $user->getEmail() ]), $user, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $user = $form->getData();
            $this->dm->persist($user);
            $this->dm->flush($user);
            return $user;
        }

        return $form->getErrors();
    }

    private function createUser()
    {
        return new $this->entityClass();
    }
}