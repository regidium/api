<?php

namespace Regidium\UserBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Regidium\CoreBundle\Exception\InvalidFormException;
use Regidium\UserBundle\Form\Type\UserType;
use Regidium\UserBundle\Document\User;

class UserHandler implements UserHandlerInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var DocumentManager
    */
    private $dm;

    /**
     * @var string
     */
    private $entityClass;


    /**
     * @var string
     */
    private $repository;

    public function __construct(FormFactoryInterface $formFactory, ManagerRegistry $mr, $entityClass)
    {
        $this->formFactory = $formFactory;
        $this->dm = $mr->getManager();
        $this->entityClass = $entityClass;
        $this->repository = $this->dm->getRepository($this->entityClass);
    }

    /**
     * Get a user.
     *
     * @param int $id
     *
     * @return User
     */
    public function get($id)
    {
        return $this->repository->find($id);
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
     * @return PageInterface
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
     * Processes the form.
     *
     * @param User   $user
     * @param array  $parameters
     * @param string $method
     *
     * @return User
     *
     * @throws \Regidium\CoreBundle\Exception\InvalidFormException
     */
    private function processForm(User $user, array $parameters, $method = 'PUT')
    {
        $form = $this->formFactory->create(new UserType(), $user, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $user = $form->getData();
            $this->dm->persist($user);
            $this->dm->flush($user);
            return $user;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createUser()
    {
        return new $this->entityClass();
    }
}