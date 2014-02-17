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
 * @todo Update response for HTML format
 *
 * @package Regidium\UserBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Agent")
 */
class WidgetAgentController extends AbstractController
{
    /**
     * List all agents in widget.
     *
     * @todo Проверять возможность просмотра для пользователя
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "List all agents in widget",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $uid Widget UID
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing users.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many users to return.")
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
                    'accept_chats' => $agent->getAcceptChats(),
                    'model_type' => $agent->getModelType()
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
     * @param Request $request   Request object
     * @param string  $uid       Widget UID
     * @param string  $agent_uid Agent UID
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

        if (!$agent) {
            $statusCode = Codes::HTTP_OK;

            $person = $this->get('regidium.agent.handler')->post(
                $widget,
                $this->prepareAgentData($request, $request->request->get('password', null))
            );
        } else {
            $statusCode = Codes::HTTP_OK;

            $password = $request->request->get('password', null);
            if ($agent->getPerson()->getPassword() != null && $password == null) {
                $password = $agent->getPerson()->getPassword();
            }

            $person = $this->get('regidium.agent.handler')->put(
                $agent,
                $this->prepareAgentData($request, $password)
            );
        }

        if (!$person instanceof Person) {
            return $this->sendError($person);
        }

        return  $this->send($person, $statusCode);
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
