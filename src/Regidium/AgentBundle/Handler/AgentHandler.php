<?php

namespace Regidium\AgentBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\AgentBundle\Form\AgentForm;
use Regidium\CommonBundle\Document\Agent;

class AgentHandler extends AbstractHandler
{
    /**
     * Создание новой сущности
     *
     * @param array $data
     *
     * @return string|array|Agent
     */
    public function post(array $data)
    {
        $entity = $this->createEntity();

        return $this->processForm($entity, $data, 'POST');
    }

    /**
     * Изменение агента.
     *
     * @param Agent $agent
     * @param array $parameters
     *
     * @return string|array|Agent
     */
    public function put(Agent $agent, array $parameters)
    {
        return $this->processForm($agent, $parameters, 'PUT');
    }

    /**
     * @todo Принимать сущность
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
            ->field('external_service.'.$provider.'.data.id')->equals($id)
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
     * @return string|array|Agent
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