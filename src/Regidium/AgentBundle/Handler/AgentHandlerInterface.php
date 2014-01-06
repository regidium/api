<?php

namespace Regidium\AgentBundle\Handler;

use Regidium\AgentBundle\Document\Agent;

interface AgentHandlerInterface
{
    /**
     * Get a Agent given the uid
     *
     * @api
     *
     * @param array $criteria
     *
     * @return array
     */
    public function get(array $criteria);

    /**
     * Get list of agents
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Agent, creates a new Agent.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return Agent
     */
    public function post(array $parameters);

    /**
     * Edit a Agent.
     *
     * @api
     *
     * @param Agent  $agent
     * @param array $parameters
     *
     * @return Agent
     */
    public function put(Agent $agent, array $parameters);

    /**
     * Partially update a Agent.
     *
     * @api
     *
     * @param Agent  $agent
     * @param array $parameters
     *
     * @return Agent
     */
    public function patch(Agent $agent, array $parameters);
}