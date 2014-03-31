<?php

namespace Regidium\AgentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Agent;

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

        return $this->send($agent);
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
            'status' => intval($request->get('status', Agent::STATUS_DEFAULT))
        ];
    }
}
