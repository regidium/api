<?php

namespace Regidium\AgentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Agent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Agent controller
 *
 * @todo Security
 *
 * @package Regidium\AgentBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Agent")
 */
class AgentController extends AbstractController
{
    /**
     * Получение детальной информации об агенте.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Получение детальной информации об агенте",
     *   output = "Regidium\CommonBundle\Document\Agent",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string $uid UID агента
     *
     * @return View
     *
     */
    public function getAction($uid)
    {
        $agent = $this->get('regidium.agent.handler')->one(['uid' => $uid]);
        if (!$agent) {
            return $this->sendError('Agent not found!');
        }

        return $this->send($agent->toArray());
    }

    /**
     * Аутентификация агента
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Аутентификация агента",
     *   output = "Regidium\CommonBundle\Document\Agent",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return View
     *
     */
    public function postLoginAction(Request $request)
    {
        $agentProvider = $this->get('regidium.agent.provider');

        $email = $request->request->get('email');
        $pwd = $request->request->get('password');

        $data = $agentProvider->loadUserByUsername($email);

        if (!$data instanceof Agent){
            return $data;
        }else{
            return ($data->getPassword() === $pwd) ? $data : $exception = ['error' => 'Wrong pwd'];
        }
    }

    /**
     * Тестовый метод
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Тестовый метод",
     *   output = "Regidium\CommonBundle\Document\Agent",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @return View
     *
     */
    public function getLoginAction()
    {
        $agentProvider = $this->get('regidium.agent.provider');

        $email = 'dummy.agent@email.com';
        $pwd = sha1('123456');

        $data = $agentProvider->loadUserByUsername($email);

        if (!$data instanceof Agent){
            return $data;
        }else{
            return ($data->getPassword() === $pwd) ? $data : $exception = ['error' => 'Wrong pwd'];
        }
    }

    /**
     * Создание нового агента.
     *
     * @ApiDoc(
     *   resource = true,
     *   uri = "/api/v1/agents",
     *   link = "/api/v1/agents",
     *   description = "Создание нового агента",
     *   input = "Regidium\AgentBundle\Form\AgentForm",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request Request объект
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        // Создаем новый виджет
        $widget = $this->get('regidium.widget.handler')->post();
        if (!$widget instanceof Widget) {
            return $this->sendError('Server Error!');
        }

        $agent_data = $this->prepareAgentData($request, $request->request->get('password', null));
        $agent_data['widget_uid'] = $widget->getUid();

        // Создаем агента и связываем его с новым виджетом
        $agent = $this->get('regidium.agent.handler')->post($agent_data);
        if (!$agent instanceof Agent) {
            return $this->sendError($agent);
        }

        $confirmation =  $this->get('regidium.confirmation.handler')->post($agent);

        $this->get('regidium.mail.handler')->post([
            'receivers' => [$agent->getEmail()],
            'title' => 'Registered Agent',
            'template' => 'RegidiumMailBundle:Agent/Notification:registered_agent.html.twig',
            'data' => ['agent' => $agent->toArray(),'confirmation' => $confirmation->toArray()]
        ]);

        return $this->send($agent->toArray());
    }

    protected function prepareAgentData(Request $request, $password)
    {
        return [
            'first_name' => strval($request->get('first_name', null)),
            'last_name' => strval($request->get('last_name', null)),
            'avatar' => strval($request->get('avatar', null)),
            'email' => strval($request->get('email', null)),
            'password' => $password,
            'job_title' => strval($request->get('job_title', '')),
            'accept_chats' => boolval($request->get('accept_chats', true)),
            'type' => intval($request->get('type', Agent::TYPE_ADMINISTRATOR)),
            'status' => intval($request->get('status', Agent::STATUS_OFFLINE)),
            'active' => intval($request->get('active', Agent::STATUS_NOT_ACTIVATED)),
            'render_visitors_period' => intval($request->get('render_visitors_period', Agent::RENDER_VISITORS_PERIOD_SESSION)),
            'notifications' => $request->get('notifications', [])
        ];
    }
}
