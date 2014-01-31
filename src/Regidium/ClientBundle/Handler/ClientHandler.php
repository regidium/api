<?php

namespace Regidium\ClientBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\ClientBundle\Form\ClientForm;
use Regidium\CommonBundle\Document\Client;

class ClientHandler extends AbstractHandler
{
    /**
     * Get one client by criteria.
     *
     * @param array $criteria
     *
     * @return Client
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get clients by criteria.
     *
     * @param array $criteria
     *
     * @return Client
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Get a list of clients.
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
     * Create a new client.
     *
     * @param array $parameters
     *
     * @return Client
     */
    public function post(array $parameters)
    {
        $client = $this->createClient();

        return $this->processForm($client, $parameters, 'POST');
    }

    /**
     * Edit a client.
     *
     * @param Client  $client
     * @param array $parameters
     *
     * @return Client
     */
    public function put(Client $client, array $parameters)
    {
        return $this->processForm($client, $parameters, 'PUT');
    }

    /**
     * Partially update a client.
     *
     * @param Client  $client
     * @param array $parameters
     *
     * @return Client
     */
    public function patch(Client $client, array $parameters)
    {
        return $this->processForm($client, $parameters, 'PATCH');
    }

    /**
     * Remove exist Client
     *
     * @param string $criteria
     *
     * @return bool|int
     */
    public function delete($criteria) {
        $client = $this->one($criteria);
        if (!$client instanceof Client) {
            return 404;
        }

        try {
            $this->dm->remove($client);
            $this->dm->flush();
            return 200;
        } catch (\Exception $e) {
            return 500;
        }
    }

    /**
     * Save edit Client
     *
     * @param Client  $client
     *
     * @return Client
     */
    public function edit(Client $client) {
        $this->dm->flush($client);
        return $client;
    }

    /**
     * Processes the form.
     *
     * @param Client $client
     * @param array  $parameters
     * @param string $method
     *
     * @return Client|\Symfony\Component\Form\FormError[]
     *
     */
    private function processForm(Client $client, array $parameters, $method = 'PUT')
    {
        $form = $this->formFactory->create(new ClientForm(), $client, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {
            $client = $form->getData();
            $this->dm->persist($client);
            $this->dm->flush($client);
            return $client;
        }

        return $form->getErrors();
    }

    private function createClient()
    {
        return new $this->entityClass();
    }
}