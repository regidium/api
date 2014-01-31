<?php

namespace Regidium\AgentBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\AgentBundle\Form\AgentForm;
use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\Agent;

class AgentHandler extends AbstractHandler
{
    /**
     * Get one agent by criteria.
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
     * Get agents by criteria.
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
     * Get list of agents.
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
     * Create new agent.
     *
     * @param array $parameters
     *
     * @return Agent
     */
    public function post(array $parameters)
    {
        $agent = $this->createEntity();

        return $this->processForm($agent, $parameters, 'POST');
    }

    /**
     * Edit agent.
     *
     * @param Agent $agent
     * @param array $parameters
     *
     * @return Agent
     */
    public function put(Agent $agent, array $parameters)
    {
        return $this->processForm($agent, $parameters, 'PUT');
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
     *
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
     * @param Agent   $agent
     * @param array  $parameters
     * @param string $method
     *
     * @return Agent|\Symfony\Component\Form\FormError[]
     *
     */
    private function processForm(Agent $agent, array $parameters, $method = 'PUT')
    {
        $agentParameters = [
            'job_title' => isset($parameters['job_title']) ? $parameters['job_title'] : '',
            'accept_chats' => isset($parameters['accept_chats']) ? $parameters['accept_chats'] : true,
            'status' => isset($parameters['status']) ? $parameters['status'] : Agent::STATUS_DEFAULT
        ];

        $formAgent = $this->formFactory->create(new AgentForm(), $agent, array('method' => $method));
        $formAgent->submit($agentParameters, 'PATCH' !== $method);
        if ($formAgent->isValid()) {
            $agent = $formAgent->getData();
            $personParameters = [
                'fullname' => isset($parameters['fullname']) ? $parameters['fullname'] : '',
                'avatar' => isset($parameters['avatar']) ? $parameters['avatar'] : '',
                'email' => isset($parameters['email']) ? $parameters['email'] : '',
                'password' => isset($parameters['password']) ? $parameters['password'] : '',
                'status' => isset($parameters['status']) ? $parameters['status'] : '',
                'country' => isset($parameters['country']) ? $parameters['country'] : '',
                'city' => isset($parameters['city']) ? $parameters['city'] : '',
                'ip' => isset($parameters['ip']) ? $parameters['ip'] : '',
                'os' => isset($parameters['os']) ? $parameters['os'] : '',
                'browser' => isset($parameters['browser']) ? $parameters['browser'] : '',
                'keyword' => isset($parameters['keyword']) ? $parameters['keyword'] : '',
                'language' => isset($parameters['language']) ? $parameters['language'] : ''
            ];

            $formPerson = $this->formFactory->create(new PersonForm([ 'email_exclusion' => $agent->getEmail() ]), $agent, array('method' => $method));
            $formPerson->submit($personParameters, 'PATCH' !== $method);
            if ($formPerson->isValid()) {
                /** @var Person $person */
                $person = $formPerson->getData();
                $person->setAgent($agent);

                $this->dm->persist($person);
                $this->dm->persist($agent);

                $this->dm->flush();

                return $person;
            }

            return $this->getFormErrors($formPerson);
        }

        return $this->getFormErrors($formAgent);
    }
}