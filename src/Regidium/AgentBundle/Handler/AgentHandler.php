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
     * * Получение агентов по условию.
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
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Создание агента.
     *
     * @param Widget $widget     Виджет к которому будет прикреплен агент
     * @param array  $parameters Параметры для сохранения
     *
     * @return string|array|Person
     */
    public function post(Widget $widget, array $parameters)
    {
        $agent = $this->createEntity();
        $person = new Person();

        return $this->processForm($agent, $person, $widget, $parameters, 'POST');
    }

    /**
     * Edit agent.
     *
     * @param Agent $agent
     * @param array $parameters
     *
     * @return string|array|Person
     */
    public function put(Agent $agent, array $parameters)
    {
        $person = $agent->getPerson();
        $widget = $agent->getWidget();

        return $this->processForm($agent, $person, $widget, $parameters, 'PUT');
    }

    /**
     * Remove exist Agent
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
            $this->dm->flush();
            return 200;
        } catch (\Exception $e) {
            return 500;
        }
    }

    /**
     * Save edit Agent
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
     * Find agent by external service id
     *
     * @param string $provider External service provider
     * @param int    $id       External service agent id
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
     * Processes the form.
     *
     * @param Agent  $agent
     * @param Person $person
     * @param Widget $widget
     * @param array  $parameters
     * @param string $method
     *
     * @return string|array|Person
     *
     */
    private function processForm(Agent $agent, Person $person, Widget $widget, array $parameters, $method = 'PUT')
    {
        $agent_data = [
            'job_title' => isset($parameters['job_title']) ? $parameters['job_title'] : '',
            'type' => isset($parameters['type']) ? $parameters['type'] : Agent::TYPE_ADMINISTRATOR,
            'status' => isset($parameters['status']) ? $parameters['status'] : Agent::STATUS_DEFAULT,
            'accept_chats' => isset($parameters['accept_chats']) ? $parameters['accept_chats'] : true
        ];

        $formAgent = $this->formFactory->create(new AgentForm(), $agent, array('method' => $method));
        $formAgent->submit($agent_data, 'PATCH' !== $method);
        if ($formAgent->isValid()) {
            /** @var Agent $agent */
            $agent = $formAgent->getData();
            if (!$agent instanceof Agent) {
                return 'Server error';
            }

            $agent->setWidget($widget);

            $person_data = [
                'fullname' => isset($parameters['fullname']) ? $parameters['fullname'] : '',
                'avatar' => isset($parameters['avatar']) ? $parameters['avatar'] : '',
                'email' => isset($parameters['email']) ? $parameters['email'] : '',
                'password' => isset($parameters['password']) ? $parameters['password'] : ''
            ];

            $formPerson = $this->formFactory->create(new PersonForm([ 'email_exclusion' => $person->getEmail() ]), $person, array('method' => $method));
            $formPerson->submit($person_data, 'PATCH' !== $method);
            if ($formPerson->isValid()) {
                /** @var Person $person */
                $person = $formPerson->getData();
                $person->setAgent($agent);

                $this->dm->persist($agent);
                $this->dm->persist($person);

                $this->dm->flush();

                return $person;
            }

            return $this->getFormErrors($formPerson);
        }

        return $this->getFormErrors($formAgent);
    }
}