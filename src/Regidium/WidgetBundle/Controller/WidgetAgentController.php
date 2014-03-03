<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Agent;
use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\Widget;

/**
 * Widget Agent controller
 *
 * @package Regidium\UserBundle\Controller
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
     * @param Request $uid UID виджета
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Смещение списка.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="Кочиество элементов в списке.")
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
        $agents = $widget->getAgents()->getValues();

        $return = [];

        foreach($agents as $agent) {
            $person = $agent->getPerson();
            $return[] = [
                'uid' => $person->getUid(),
                'fullname' => $person->getFullname(),
                'email' => $person->getEmail(),
                'avatar' => $person->getAvatar(),
                'agent' => [
                    'uid' => $agent->getUid(),
                    'status' => $agent->getStatus(),
                    'job_title' => $agent->getJobTitle(),
                    'type' => $agent->getType(),
                    'accept_chats' => $agent->getAcceptChats()
                ]
            ];
        }

        return $this->sendArray($return);
    }


    /**
     * Create agent from submitted data.
     *
     * @deprecated
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Create agent from submitted data.",
     *   input = "Regidium\AgentBundle\Form\AgentForm",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request Request object
     * @param Request $uid Widget UID
     *
     * @return View
     */
    public function postAction(Request $request, $uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);

        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $person = $this->get('regidium.agent.handler')->post($widget, $this->prepareAgentData($request, $request->request->get('password', null)));

        if (!$person instanceof Person) {
            return $this->sendError($person);
        }

        return $this->send($person, Codes::HTTP_CREATED);
    }

    /**
     * Update existing agent from submitted data or create new agent.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Update existing agent from submitted data or create new agent.",
     *   input = "Regidium\AgentBundle\Form\AgentForm",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request    Request object
     * @param string  $uid        Widget UID
     * @param string  $person_uid Person UID
     *
     * @return View
     *
     */
    public function putAction(Request $request, $uid, $person_uid = null)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);

        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $person = null;
        if ($person_uid) {
            $person = $this->get('regidium.person.handler')->one(['uid' => $person_uid]);
        }

        if (!$person) {
            $status_code = Codes::HTTP_OK;

            $person = $this->get('regidium.agent.handler')->post(
                $widget,
                $this->prepareAgentData($request, $request->request->get('password', null))
            );
        } else {
            $status_code = Codes::HTTP_OK;

            $password = $request->request->get('password', null);
            if ($person->getPassword() != null && $password == null) {
                $password = $person->getPassword();
            }

            $person = $this->get('regidium.agent.handler')->put(
                $person,
                $this->prepareAgentData($request, $password)
            );
        }

        if (!$person instanceof Person) {
            return $this->sendError($person);
        }

        $return = [
            'uid' => $person->getUid(),
            'fullname' => $person->getFullname(),
            'avatar' => $person->getAvatar(),
            'email' => $person->getEmail(),
            'country' => $person->getCountry(),
            'city' => $person->getCity(),
            'status' => $person->getStatus(),
            'agent' => [
                'uid' => $person->getAgent()->getUid(),
                'job_title' => $person->getAgent()->getJobTitle(),
                'status' => $person->getAgent()->getStatus(),
                'type' => $person->getAgent()->getType(),
                'accept_chats' => $person->getAgent()->getAcceptChats()
            ]
        ];

        return $this->send($return, $status_code);
    }

    protected function prepareAgentData(Request $request, $password)
    {
        return [
            'fullname' => $request->request->get('fullname', null),
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
