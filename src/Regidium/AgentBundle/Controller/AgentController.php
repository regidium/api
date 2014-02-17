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
     * Получаение списка агентов.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Получаение списка агентов.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing agents.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many agents to return.")
     *
     * @return array
     */
    public function cgetAction()
    {
        $agents = $this->get('regidium.agent.handler')->all();

        return $this->sendArray(array_values($agents));
    }

    /**
     * Получение детальной информации об агенте.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Получение детальной информации об агенте",
     *   output = "Regidium\CommonBundle\Document\Agent",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param string $uid Agent UID
     *
     * @return array
     *
     */
    public function getAction($uid)
    {
        $agent = $this->getOr404(['uid' => $uid]);

        return $this->send($agent);
    }

    /**
     * Создание нового агента.
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
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        // Создаем виджет для нового пользователя
        $widget = $this->get('regidium.widget.handler')->post($request->request->all());
        if (!$widget instanceof Widget) {
            return $this->sendError('Server Error!');
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
     * @param Request $request Request object
     * @param string  $uid     Agent UID
     *
     * @return View
     *
     */
    public function putAction(Request $request, $uid)
    {
        $person = $this->get('regidium.person.handler')->one(['uid' => $uid]);

        if (!$person) {
            $statusCode = Codes::HTTP_CREATED;

            // Создаем виджет для нового пользователя
            $widget = $this->get('regidium.widget.handler')->post($request->request->all());
            if (!$widget instanceof Widget) {
                return $this->sendError('Server Error!');
            }

            $person = $this->get('regidium.agent.handler')->post(
                $widget,
                $this->prepareAgentData($request, $request->request->get('password', null))
            );
        } else {
            $statusCode = Codes::HTTP_OK;

            $password = $request->request->get('password', null);
            if ($person->getPassword() != null && $password == null) {
                $password = $person->getPassword();
            }

            $person = $this->get('regidium.agent.handler')->put(
                $person->getAgent(),
                $this->prepareAgentData($request, $password)
            );
        }

        if (!$person instanceof Person) {
            return $this->sendError($person);
        }

        return  $this->send($person, $statusCode);
    }

    /**
     * Remove existing agent.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Remove existing agent.",
     *   statusCodes = {
     *     200 = "Returned when successful"
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

    /**
     * Fetch a agent or throw an 404.
     *
     * @param array $criteria
     *
     * @return Agent|int
     *
     */
    protected function getOr404(array $criteria)
    {
        $agent = $this->get('regidium.agent.handler')->one($criteria);
        if (!$agent) {
            return $this->sendError('The resource was not found.');
        }

        return $agent;
    }

    protected function prepareAgentData(Request $request, $password)
    {
        return [
            'fullname' => $request->get('fullname', null),
            'job_title' => $request->get('job_title', null),
            'avatar' => $request->get('avatar', null),
            'email' => $request->get('email', null),
            'password' => $password,
            'type' => $request->get('type', Agent::TYPE_ADMINISTRATOR),
            'status' => $request->get('status', Agent::STATUS_DEFAULT),
            'accept_chats' => $request->get('accept_chats', true)
        ];
    }
}
