<?php

namespace Regidium\AgentBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\AgentBundle\Form\AgentForm;
use Regidium\AgentBundle\Form\AgentSessionForm;
use Regidium\CommonBundle\Document\Agent;
use Regidium\CommonBundle\Document\Session;
use Regidium\CommonBundle\Document\Mail;

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
        return $this->processForm($this->createEntity(), $data, 'POST');
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
    public function online(Agent $agent, $data = []) {
        $agent->setStatus(Agent::STATUS_ONLINE);

        $current_session = $agent->getCurrentSession();
        if (!$current_session) {
            $agent_session_form = $this->form_factory->create(new AgentSessionForm(), new Session(), ['method' => 'POST']);
            $agent_session_form->submit($data, false);
            $current_session = $agent_session_form->getData();
            $current_session->setAgent($agent);
            $this->dm->persist($current_session);
            $this->dm->flush($current_session);

            $agent->setCurrentSession($current_session);
        }

        $current_session->setStatus(Session::STATUS_ONLINE);
        $current_session->setLastVisit(time());
        $current_session->setEndedAt(null);
        $this->dm->persist($current_session);

        $this->dm->flush();

        return $agent;
    }

    /**
     * Агент общеется в чате
     *
     * @param Agent $agent
     *
     * @return Agent
     */
    public function chatting(Agent $agent) {
        $agent->setStatus(Agent::STATUS_CHATTING);

        $current_session = $agent->getCurrentSession();
        $current_session->setEndedAt(time());
        $current_session->setStatus(Session::STATUS_OFFLINE);
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
        $current_session = $agent->getCurrentSession();
        $current_session->setEndedAt(time());
        $current_session->setStatus(Session::STATUS_OFFLINE);

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
     * Создание нового письма для отправки агенту
     *
     * @param Agent $agent
     * @param array $data
     *
     * @return Mail
     */
    public function addMail(Agent $agent, array $data)
    {
        /** @var Mail $mail */
        $mail = $this->createEntity();
        $mail->setTitle(isset($data['title']) ? $data['title'] : '');
        $mail->setBody(isset($data['body']) ? $data['body'] : '');

        $data = [];
        $data['agent_uid'] = $agent->getUid();
        $data['widget_uid'] = $agent->getWidget()->getUid();
        $mail->setData($data);

        $this->dm->persist($mail);
        $this->dm->flush($mail);

        return $mail;
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
        $form = $this->form_factory->create(new AgentForm(), $agent, ['method' => $method]);
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