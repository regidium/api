<?php

namespace Regidium\AgentBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\AgentBundle\Form\AgentForm;
use Regidium\AgentBundle\Document\Agent;

class AgentHandler extends AbstractHandler implements AgentHandlerInterface
{
    /**
     * Get one agent  by criteria.
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
     * Get a list of agents.
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
     * Create a new agent.
     *
     * @param array $parameters
     *
     * @return Agent
     */
    public function post(array $parameters)
    {
        $agent = $this->createAgent();

        return $this->processForm($agent, $parameters, 'POST');
    }

    /**
     * Edit a agent.
     *
     * @param Agent  $agent
     * @param array $parameters
     *
     * @return Agent
     */
    public function put(Agent $agent, array $parameters)
    {
        return $this->processForm($agent, $parameters, 'PUT');
    }

    /**
     * Partially update a agent.
     *
     * @param Agent  $agent
     * @param array $parameters
     *
     * @return Agent
     */
    public function patch(Agent $agent, array $parameters)
    {
        return $this->processForm($agent, $parameters, 'PATCH');
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
        return $this->dm->createQueryBuilder('Regidium\UserBundle\Document\User')
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
        $form = $this->formFactory->create(new AgentForm([ 'email_exclusion' => $agent->getEmail() ]), $agent, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $agent = $form->getData();
            $this->dm->persist($agent);
            $this->dm->flush($agent);
            return $agent;
        }

        $return = [];
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $return[] = $error->getCause();
        }

        return $return;
    }

    private function createAgent()
    {
        return new $this->entityClass();
    }
}