<?php

namespace Regidium\AgentBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\AgentBundle\Form\AgentForm;
use Regidium\AgentBundle\Form\AgentSessionForm;
use Regidium\CommonBundle\Document\Agent;
use Regidium\CommonBundle\Document\AgentSession;

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
        // Записываем последний визит агента
        $entity->setLastVisit(time());
        return $this->processForm($entity, $data, 'POST');
    }

    /**
     * Изменение агента.
     *
     * @param Agent $agent
     * @param array $data
     *
     * @return string|array|Agent
     */
    public function put(Agent $agent, array $data)
    {
        return $this->processForm($agent, $data, 'PUT');
    }

    /**
     * Удаление агента
     *
     * @param Agent $agent
     *
     * @return bool|int
     */
    public function delete($agent) {
        try {
            $this->dm->remove($agent);
            $this->dm->flush($agent);

            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Подключение агента
     *
     * @param Agent $agent
     * @param array $data
     *
     * @return Agent
     */
    public function online(Agent $agent) {
        $agent->setStatus(Agent::STATUS_ONLINE);
        $agent->setLastVisit(time());

        $agent_session = new AgentSession();
        $agent_session_form = $this->formFactory->create(new AgentSessionForm(), $agent_session, ['method' => 'POST']);
        $agent_session_form->submit($data, false);
        $agent_session = $agent_session_form->getData();
        $agent->setSession($agent_session);

        $this->edit($agent);

        return $agent;
    }

    /**
     * Отключение чата
     *
     * @param Agent $agent
     *
     * @return Agent
     */
    public function offline(Agent $agent) {
        $agent->setStatus(Agent::STATUS_OFFLINE);

        $this->edit($agent);

        return $agent;
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