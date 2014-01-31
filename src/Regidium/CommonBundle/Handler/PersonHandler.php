<?php

namespace Regidium\CommonBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;

use Regidium\CommonBundle\Form\PersonForm;
use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\Agent;
use Regidium\CommonBundle\Document\User;

class PersonHandler extends AbstractHandler
{
    /**
     * Get one person by criteria.
     *
     * @param array $criteria
     *
     * @return Person
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get persons by criteria.
     *
     * @param array $criteria
     *
     * @return Person[]
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Get list of persons.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return Person[]
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy([], null, $limit, $offset);
    }

    /**
     * Create new person.
     *
     * @param array $parameters
     *
     * @return Person
     */
    public function post(array $parameters)
    {
        $person = $this->createEntity();

        return $this->processForm($person, $parameters, 'POST');
    }

    /**
     * Edit a person.
     *
     * @param Person $person
     * @param array  $parameters
     *
     * @return Person
     */
    public function put(Person $person, array $parameters)
    {
        return $this->processForm($person, $parameters, 'PUT');
    }

    /**
     * Remove exist person
     *
     * @todo Удалять Agent, User, Visitor
     *
     * @param string $criteria
     *
     * @return bool|int
     */
    public function delete($criteria) {
        $person = $this->one($criteria);
        if (!$person instanceof Person) {
            return 404;
        }

        try {
            $this->dm->remove($person);
            $this->dm->flush();
            return 200;
        } catch (\Exception $e) {
            return 500;
        }
    }

    /**
     * Save new person
     *
     * @param Person $person
     *
     * @return Person
     */
    public function save(Person $person) {
        $this->dm->persist($person);
        $this->dm->flush($person);

        return $person;
    }

    /**
     * Save edited person
     *
     * @param Person $person
     *
     * @return Person
     */
    public function edit(Person $person) {
        $this->dm->flush($person);

        return $person;
    }

    /**
     *
     * Find person by external service id
     *
     * @param string $provider External service provider
     * @param int    $id       External service user id
     *
     * @return Person|null
     */
    public function oneByExternalService($provider, $id) {
        return $this->repository->createQueryBuilder()
            ->field("external_service.{$provider}.data.id")->equals($id)
            ->getQuery()
            ->getSingleResult()
        ;
    }


    /**
     * Processes the form.
     *
     * @param Person $person
     * @param array  $parameters
     * @param string $method
     *
     * @return Person|array
     *
     */
    private function processForm(Person $person, array $parameters, $method = 'PUT')
    {
        $form = $this->formFactory->create(new PersonForm([ 'email_exclusion' => $person->getEmail() ]), $person, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            /** @var \Regidium\CommonBundle\Document\Person $person */
            $person = $form->getData();

            $agent = new Agent();
            $user = new User();
            $person->setAgent($agent);
            $person->setUser($user);
            $this->dm->persist($person);
            $this->dm->flush($person);
            return $person;
        }

        $return = [];
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $return[] = $error->getCause();
        }

        return $return;
    }
}