<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Agent;
use Regidium\CommonBundle\Document\Widget;

/**
 * Widget Agent controller
 *
 * @package Regidium\CommonBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Agent")
 */
class WidgetAgentController extends AbstractController
{
    /**
     * Получаем список агентов виджета.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Получаем список агентов виджета.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Смещение списка.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="Кочиество элементов в списке.")
     *
     *
     * @param Request $uid UID виджета
     *
     * @return View
     */
    public function cgetExistedAction($uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        /** @var Agent[] $agents */
        $agents = $widget->getAgents();

        $return = [];

        foreach($agents as $agent) {
            $return[] = $agent->toArray();
        }

        return $this->sendArray($return);
    }


    /**
     * Создание нового агента для виджета.
     *
     * @todo
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Создание нового агента для виджета.",
     *   input = "Regidium\AgentBundle\Form\AgentForm",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="first_name", requirements="\w+", nullable=true, description="Имя агента.")
     * @Annotations\QueryParam(name="last_name",  requirements="\w+", nullable=true, description="Фамилия агента.")
     * @Annotations\QueryParam(name="job_title",  requirements="\w+", nullable=true, description="Заголовок агента.")
     * @Annotations\QueryParam(name="avatar",     requirements="\w+", nullable=true, description="Аватар агента.")
     * @Annotations\QueryParam(name="email",      requirements="\w+", nullable=true, description="Email пользователя.")
     * @Annotations\QueryParam(name="password",   requirements="\w+", nullable=true, description="Пароль агента.")
     * @Annotations\QueryParam(name="type",       requirements="\d+", nullable=true, description="Тип агента.")
     * @Annotations\QueryParam(name="status",     requirements="\d+", nullable=true, description="Статус агента.")
     * @Annotations\QueryParam(name="type",                           nullable=true, description="Принимать ли чаты.")
     *
     *
     * @param Request $request Request объект
     * @param Request $uid     Widget UID
     *
     * @return View
     */
    public function postAction(Request $request, $uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);

        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $agent = $this->get('regidium.agent.handler')->post($widget, $this->prepareAgentData($request, $request->request->get('password', null)));

        if (!$agent instanceof Agent) {
            return $this->sendError($agent);
        }

        return $this->send($agent->toArray());
    }

    /**
     * Изменение существующего или создание нового агента.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Изменение существующего или создание нового агента.",
     *   input = "Regidium\AgentBundle\Form\AgentForm",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="first_name", requirements="\w+", nullable=true,  description="Имя агента.")
     * @Annotations\QueryParam(name="last_name",  requirements="\w+", nullable=true,  description="Фамилия агента.")
     * @Annotations\QueryParam(name="job_title",  requirements="\w+", nullable=true,  description="Заголовок агента.")
     * @Annotations\QueryParam(name="avatar",     requirements="\w+", nullable=true,  description="Аватар агента.")
     * @Annotations\QueryParam(name="email",      requirements="\w+", nullable=false, description="Email пользователя.")
     * @Annotations\QueryParam(name="password",   requirements="\w+", nullable=false, description="Пароль агента.")
     * @Annotations\QueryParam(name="type",       requirements="\d+", nullable=true,  description="Тип агента.")
     * @Annotations\QueryParam(name="status",     requirements="\d+", nullable=true,  description="Статус агента.")
     * @Annotations\QueryParam(name="type",                           nullable=true,  description="Принимать ли чаты.")
     *
     *
     * @param Request $request    Request объект
     * @param string  $uid        Widget UID
     * @param string  $agent_uid  Agent UID
     *
     * @return View
     *
     */
    public function putAction(Request $request, $uid, $agent_uid = null)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);

        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $agent = null;
        if ($agent_uid) {
            $agent = $this->get('regidium.agent.handler')->one(['uid' => $agent_uid]);
        }

        $password = $request->request->get('password', null);
        if ($agent && $agent->getPassword() != null && $password == null) {
            $password = $agent->getPassword();
        }

        $data = $this->prepareAgentData($request, $password);
        $data['widget_uid'] = $uid;
        if (!$agent) {
            $agent = $this->get('regidium.agent.handler')->post(
                $data
            );
        } else {
            $agent = $this->get('regidium.agent.handler')->put(
                $agent,
                $data
            );
        }

        if (!$agent instanceof Agent) {
            return $this->sendError($agent);
        }

        return $this->send($agent->toArray());
    }

    /**
     * Агент offline.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Агент offline.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string  $uid        Widget UID
     * @param string  $agent_uid  Agent UID
     *
     * @return View
     *
     */
    public function putOfflineAction($uid, $agent_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $agent = $this->get('regidium.agent.handler')->one(['uid' => $agent_uid]);
        if (!$agent instanceof Agent) {
            return $this->sendError('Agent not found!');
        }

        $this->get('regidium.agent.handler')->offline($agent);

        return $this->sendSuccess();
    }

    /**
     * Агент online.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Агент online.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string  $uid        Widget UID
     * @param string  $agent_uid  Agent UID
     *
     * @return View
     *
     */
    public function putOnlineAction(Request $request, $uid, $agent_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $agent = $this->get('regidium.agent.handler')->one(['uid' => $agent_uid]);
        if (!$agent instanceof Agent) {
            return $this->sendError('Agent not found!');
        }

        $data = $this->prepareAgentSessionData($request);
        $this->get('regidium.agent.handler')->online($agent, $data);

        return $this->sendSuccess();
    }

    /**
     * Удаление существующего агента.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Удаление существующего агента.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request    Request объект
     * @param string  $uid        Widget UID
     * @param string  $agent_uid  Agent UID
     *
     * @return View
     *
     */
    public function deleteAction(Request $request, $uid, $agent_uid = null)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $agent = $this->get('regidium.agent.handler')->one(['uid' => $agent_uid]);
        if (!$agent instanceof Agent) {
            return $this->sendError('Agent not found!');
        }

        $result = $this->get('regidium.agent.handler')->delete(
            $agent
        );

        if ($result !== true) {
            return $this->sendError($result);
        }

        return $this->sendSuccess();
    }

    /**
     * Подготовка данных об агенте из пришедших данных
     *
     * @param Request $request  Request объект
     * @param string $password Пароль
     *
     * @return array
     */
    protected function prepareAgentData(Request $request, $password)
    {
        return [
            'first_name' => strval($request->request->get('first_name', null)),
            'last_name' => strval($request->request->get('last_name', null)),
            'job_title' => strval($request->request->get('job_title', null)),
            'avatar' => strval($request->request->get('avatar', null)),
            'email' => strval($request->request->get('email', null)),
            'password' => $password,
            'type' => intval($request->request->get('type', Agent::TYPE_OPERATOR)),
            'status' => intval($request->request->get('status', Agent::STATUS_OFFLINE)),
            'accept_chats' => boolval($request->request->get('accept_chats', true)),
            'render_visitors_period' => intval($request->request->get('render_visitors_period', Agent::RENDER_VISITORS_PERIOD_SESSION)),
        ];
    }

    /**
     * Подготовка данных об агенте из пришедших данных
     *
     * @param Request $request  Request объект
     * @param string $password Пароль
     *
     * @return array
     */
    protected function prepareAgentSessionData(Request $request)
    {
        return [
            'country' => strval($request->request->get('country', null)),
            'city' => strval($request->request->get('city', null)),
            'ip' => strval($request->request->get('ip', null)),
            'device' => strval($request->request->get('device', null)),
            'os' => strval($request->request->get('os', null)),
            'browser' => strval($request->request->get('browser', null)),
            'language' => strval($request->request->get('language', null))
        ];
    }
}
