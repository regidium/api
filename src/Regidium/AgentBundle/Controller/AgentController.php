<?php

namespace Regidium\AgentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Agent;

/**
 * Agent controller
 *
 * @todo Update response for HTML format
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
     * Регистрация нового агента.
     *
     * @ApiDoc(
     *   resource = true,
     *   uri = "/api/v1/agents",
     *   link = "/api/v1/agents",
     *   description = "Регистрация нового агента.",
     *   input = "Regidium\AgentBundle\Form\AgentForm",
     *   requirements = {
     *      {"name"="fullname", "dataType"="string", "required"=true, "description"="Имя агента"},
     *      {"name"="email", "dataType"="string", "required"=true, "description"="Email агента"},
     *      {"name"="password", "dataType"="string", "required"=true, "description"="Пароль агента"}
     *   },
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
        $data = $this->prepareData($request, $request->request->get('password', null));

        // Создаем новый виджет для нового агента
        $widget = $this->get('regidium.widget.handler')->post($data['widget']);
        if (!$widget instanceof Widget) {
            return $this->sendError('Server Error!');
        }

        // Создаем персону агента
        $person = $this->get('regidium.person.handler')->post($data['person']);
        if (!$person instanceof Person) {
            return $this->sendError($person);
        }

        $data['agent']['widget_uid'] = $widget->getUid();

        // Создаем агента
        $agent = $this->get('regidium.agent.handler')->post($data['agent']);
        if (!$agent instanceof Agent) {
            return $this->sendError($agent);
        }

        return $this->send($person, Codes::HTTP_CREATED);
    }

    /**
     * Создание нового агента или изменение существующего.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Создание нового агента или изменение существующего.",
     *   input = "Regidium\AgentBundle\Form\AgentForm",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request Request object
     * @param string  $uid     Agent UID
     *
     * @return View
     *
     */
    public function putAction(Request $request, $uid)
    {
        $person = $this->get('regidium.person.handler')->one(['uid' => $uid]);

        if (!$person instanceof Person) {
            return $this->postAction($request);
        } else {
            $password = $request->request->get('password', null);
            if ($person->getPassword() != null && $password == null) {
                $password = $person->getPassword();
            }

            $data = $this->prepareData($request, $password);

            $person = $this->get('regidium.person.handler')->put(
                $person,
                $data['person']
            );

            if (!$person instanceof Person) {
                return $this->sendError($person);
            }

            $agent = $this->get('regidium.agent.handler')->put(
                $person,
                $data['agent']
            );

            if (!$agent instanceof Agent) {
                return $this->sendError($agent);
            }

            return  $this->send($person->toArray(), Codes::HTTP_OK);
        }
    }

    /**
     * @todo
     * Удаление существующего агента.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Удаление существующего агента.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string $uid Agent UID
     *
     * @return View
     *
     */
    public function deleteAction($uid)
    {
        $result = $this->get('regidium.agent.handler')->delete([ 'uid' => $uid ]);

        if ($result === 404) {
            return $this->sendError('Agent not found!');
        } elseif ($result === 500) {
            return $this->sendError('Server error!');
        }

        return $this->sendSuccess();
    }

    protected function prepareData(Request $request, $password)
    {
        return [
            'widget' => [
                'status' => intval($request->get('status', Widget::STATUS_DEFAULT)),
            ],
            'person' => [
                'fullname' => strval($request->get('fullname', null)),
                'avatar' => strval($request->get('avatar', null)),
                'email' => strval($request->get('email', null)),
                'password' => $password
            ],
            'agent' => [
                'job_title' => strval($request->get('job_title', '')),
                'accept_chats' => boolval($request->get('accept_chats', true)),
                'type' => intval($request->get('type', Agent::TYPE_ADMINISTRATOR)),
                'status' => intval($request->get('status', Agent::STATUS_DEFAULT))
            ]
        ];
    }
}
