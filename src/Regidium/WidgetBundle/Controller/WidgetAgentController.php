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
    public function cgetAction($uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);

        /** @todo вернуть ошибку */
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
     * @deprecated
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
        if (!$agent) {
            $agent = $this->get('regidium.agent.handler')->post(
                $widget,
                $this->prepareAgentData($request, $password)
            );
        } else {
            if ($agent->getPassword() != null && $password == null) {
                $password = $agent->getPassword();
            }

            $agent = $this->get('regidium.agent.handler')->put(
                $agent,
                $this->prepareAgentData($request, $password)
            );
        }

        if (!$agent instanceof Agent) {
            return $this->sendError($agent);
        }

        return $this->send($agent->toArray());
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
            'first_name' => $request->request->get('first_name', null),
            'last_name' => $request->request->get('last_name', null),
            'job_title' => $request->request->get('job_title', null),
            'avatar' => $request->request->get('avatar', null),
            'email' => $request->request->get('email', null),
            'password' => $password,
            'type' => $request->request->get('type', Agent::TYPE_OPERATOR),
            'status' => $request->request->get('status', Agent::STATUS_DEFAULT),
            'accept_chats' => $request->request->get('accept_chats', true)
        ];
    }
}
