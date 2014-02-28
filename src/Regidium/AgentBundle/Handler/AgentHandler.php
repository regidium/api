<?php

namespace Regidium\AgentBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\AgentBundle\Form\AgentForm;
use Regidium\CommonBundle\Form\PersonForm;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\Agent;

class AgentHandler extends AbstractHandler
{
    /**
     * Получение одного агента по условию.
     *
     * @param array $criteria
     *
     * @return Agent
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Получение агентов по условию.
     *
     * @param array $criteria
     *
     * @return Agent
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * * Получение списка всех агентов.
     *
     * @param int $limit  limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
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
     * Изменение агента.
     *
     * @param Person $person
     * @param array  $parameters
     *
     * @return string|array|Person
     */
    public function put(Person $person, array $parameters)
    {
        $agent = $person->getAgent();

        return $this->processForm($agent, $parameters, 'PUT');
    }

    /**
     * Удаление агента
     *
     * @param string $criteria
     *
     * @return bool|int
     */
    public function delete($criteria) {
        $agent = $this->one($criteria);
        if (!$agent instanceof Agent) {
            return 404;
        }

        try {
            $this->dm->remove($agent);
            $this->dm->flush($agent);

            return 200;
        } catch (\Exception $e) {
            return 500;
        }
    }

    /**
     * Save edit Agent
     *
     * @todo Remove
     *
     * @param Agent $agent
     *
     * @return Agent
     */
    public function edit(Agent $agent) {
        $this->dm->flush($agent);
        return $agent;
    }

    /**
     * Поиск агента по данным стороннего сервиса
     *
     * @param string $provider Провайдер стороннего сервиса
     * @param int    $id       Id агента на стороннем сервисе
     *
     * @return Agent|null
     */
    public function oneByExternalService($provider, $id) {
        return $this->repository
            ->field("external_service.{$provider}.data.id")->equals($id)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    /**
     * Обработка формы.
     *
     * @param Agent  $agent
     * @param array  $data
     * @param string $method
     *
     * @return string|array|Person
     *
     */
    public function processForm(Agent $agent, array $data, $method = 'PUT')
    {
        $form = $this->formFactory->create(new AgentForm(), $agent, ['method' => $method]);
        $form->submit($data, 'PATCH' !== $method);
        if ($form->isValid()) {
            /** @var Agent $agent */
            $agent = $form->getData();
            if (!$agent instanceof Agent) {
                return 'Server error';
            }

            $widget = $this->dm->getRepository('Regidium\CommonBundle\Document\Widget')->findOneBy(['uid' => $form->get('widget_uid')->getData()]);
            $agent->setWidget($widget);

            $this->dm->persist($agent);
            $this->dm->flush($agent);

            return $agent;
        }

        return $this->getFormErrors($form);
    }
}