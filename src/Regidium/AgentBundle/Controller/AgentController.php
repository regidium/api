<?php

namespace Regidium\AgentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
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
     * List all agents.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "List all agents.",
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
     * Get single agent.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Get single agent",
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

        return $agent;
    }

    /**
     * Create agent from submitted data.
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
        $person = $this->get('regidium.agent.handler')->post($this->prepareAgentData($request, $request->request->get('password', null)));

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

            $person = $this->get('regidium.agent.handler')->post(
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
            'fullname' => $request->request->get('fullname', null),
            'avatar' => $request->request->get('avatar', null),
            'email' => $request->request->get('email', null),
            'password' => $password,
            'status' => $request->request->get('status', Agent::STATUS_DEFAULT),
            'country' => $request->request->get('country', null),
            'city' => $request->request->get('city', null),
            'ip' => $request->request->get('ip', null),
            'os' => $request->request->get('os', null),
            'browser' => $request->request->get('browser', null),
            'keyword' => $request->request->get('keyword', null),
            'language' => $request->request->get('language', 'ru'),
            'accept_chats' => $request->request->get('accept_chats', true)
        ];
    }
}
