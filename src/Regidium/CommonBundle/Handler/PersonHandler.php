<?php

namespace Regidium\CommonBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\CommonBundle\Form\PersonForm;
use Regidium\CommonBundle\Document\Person;

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
     * Создание новой сущности
     *
     * @param array $data
     *
     * @return object
     */
    public function post(array $data)
    {
        $entity = $this->createEntity();

        return $this->processForm($entity, $data, 'POST');
    }

    /**
     * Edit a person.
     *
     * @param Person $person
     * @param array  $data
     *
     * @return Person
     */
    public function put(Person $person, array $data)
    {
        return $this->processForm($person, $data, 'PUT');
    }

    /**
     * Измнение авторизационных данных пользователя.
     *
     * @param Person $person
     * @param array  $data
     *
     * @return Person
     */
    public function auth(Person $person, array $data)
    {
        if (isset($data['email'])) {
            $person->setEmail($data['email']);
        }

        if (isset($data['fullname'])) {
            $person->setFullname($data['fullname']);
        }

        $this->dm->persist($person);
        $this->dm->flush($person);

        return $person;
    }

    /**
     * Remove exist person
     *
     * @todo Удалять Agent, User
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
     * Обработка формы.
     *
     * @param Person $person
     * @param array  $data
     * @param string $method
     *
     * @return Person|array
     *
     */
    public function processForm(Person $person, array $data, $method = 'PUT')
    {
        $form = $this->formFactory->create(new PersonForm(['email_exclusion' => $person->getEmail()]), $person, ['method' => $method]);
        $form->submit($data, 'PATCH' !== $method);
        if ($form->isValid()) {
            /** @var Person $person */
            $person = $form->getData();

            $this->dm->persist($person);
            $this->dm->flush($person);

            return $person;
        }

        return $this->getFormErrors($form);
    }
}