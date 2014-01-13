<?php

namespace Regidium\AgentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\AgentBundle\Form\AgentForm;
use Regidium\AgentBundle\Document\Agent;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Agent controller
 *
 * @todo Update response for HTML format
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
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing agents.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many agents to return.")
     *
     * @Annotations\View(
     *  templateVar="agents"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function cgetAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $return = [];
        $agents = $this->get('regidium.agent.handler')->all();
        foreach ($agents as $agent) {
            $return[] = [
                'uid' => $agent->getUid(),
                'avatar' => $agent->getAvatar(),
                'fullname' => $agent->getFullname(),
                'email' => $agent->getEmail(),
                'status' => $agent->getStatus(),
                'type' => $agent->getType(),
                'accept_chats' => $agent->getAcceptChats(),
                'amount_chats' => $agent->getAmountChats()
            ];
        }
        return $this->view($return);
    }

    /**
     * Get single agent.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a agents for a given uid",
     *   output = "Regidium\AgentBundle\Document\Agent",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the agent is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="agent")
     *
     * @param int     $uid      the agent uid
     *
     * @return array
     *
     * @throws NotFoundHttpException when agent not exist
     */
    public function getAction($uid)
    {
        $agent = $this->getOr404(['uid' => $uid]);

        return $agent;
    }

    /**
     * Presents the form to use to create a new agent.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function newAction()
    {
        return $this->createForm(new AgentForm());
    }

    /**
     * Create a agent from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new agent from the submitted data.",
     *   input = "Regidium\AgentBundle\Form\AgentForm",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "RegidiumAgentBundle:Agent:newAgent.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postAction(Request $request)
    {
        $result = $this->get('regidium.agent.handler')->post([
            'email' => $request->request->get('email', null),
            'fullname' => $request->request->get('fullname', null),
            'avatar' => $request->request->get('avatar', null),
            'password' => $request->request->get('password', null),
            'type' => $request->request->get('type', Agent::TYPE_OPERATOR),
            'status' => $request->request->get('status', Agent::STATUS_DEFAULT),
            'accept_chats' => $request->request->get('accept_chats', true),
        ]);

        if (!$result instanceof Agent) {
            return $this->view(['errors' => $result]);
        }

        $routeOptions = array(
            'uid' => $result->getUid(),
            '_format' => $request->get('_format')
        );

        return $this->routeRedirectView('api_1_get_agent', $routeOptions, Codes::HTTP_CREATED);

    }

    /**
     * Update existing agent from the submitted data or create a new agent at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Regidium\AgentBundle\Form\AgentForm",
     *   statusCodes = {
     *     201 = "Returned when the agent is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "RegidiumAgentBundle:Agent:editAgent.html.twig",
     *  templateVar = "form"
     * )
     *
     * @todo Update
     *
     * @deprecated Will be updated
     *
     * @param Request $request the request object
     * @param int     $uid      the agent uid
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when agent not exist
     */
    public function putAction(Request $request, $uid)
    {
        try {
            if (!($agent = $this->get('regidium.agent.handler')->one(['uid' => $uid]))) {
                $statusCode = Codes::HTTP_CREATED;
                $post = [
                    'email' => $request->request->get('email', null),
                    'fullname' => $request->request->get('fullname', null),
                    'avatar' => $request->request->get('avatar', null),
                    'password' => $request->request->get('password', null),
                    'type' => (int)$request->request->get('type', Agent::TYPE_OPERATOR),
                    'status' => (int)$request->request->get('status', Agent::STATUS_DEFAULT),
                    'accept_chats' => (bool)$request->request->get('accept_chats', true),
                ];
                $agent = $this->get('regidium.agent.handler')->post(
                    $post
                );
            } else {
                $statusCode = Codes::HTTP_OK;
                $password = $request->request->get('password', null);
                if ($agent->getPassword() != null && $password == null) {
                    $password = $agent->getPassword();
                }
                $put = [
                    'email' => $request->request->get('email', null),
                    'fullname' => $request->request->get('fullname', null),
                    'avatar' => $request->request->get('avatar', null),
                    'password' => $password,
                    'type' => (int)$request->request->get('type', Agent::TYPE_OPERATOR),
                    'status' => (int)$request->request->get('status', Agent::STATUS_DEFAULT),
                    'accept_chats' => (bool)$request->request->get('accept_chats', true),
                ];
                $agent = $this->get('regidium.agent.handler')->put(
                    $agent,
                    $put
                );
            }

            if (!$agent instanceof Agent) {
                return  $this->view(['errors' => $agent]);
            }

            $routeOptions = array(
                'uid' => $agent->getUid(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_agent', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing agent from the submitted data or create a new agent at a specific location.
     *
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Regidium\AgentBundle\Form\Type\AgentType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "RegidiumAgentBundle:Agent:editAgent.html.twig",
     *  templateVar = "form"
     * )
     *
     * @todo Remove
     *
     * @deprecated Will be removed
     *
     * @param Request $request the request object
     * @param int     $uid      the agent uid
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when agent not exist
     */
    public function patchAction(Request $request, $uid)
    {
        try {
            $agent = $this->get('regidium.agent.handler')->patch(
                $this->getOr404(['uid' => $uid]),
                $request->request->all()
            );

            $routeOptions = array(
                'uid' => $agent->getUid(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_agent', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Remove existing agent.
     *
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Always returned",
     *   }
     * )
     *
     * @param int   $uid  Agent uid
     *
     * @return View
     *
     */
    public function deleteAction($uid)
    {
        $result = $this->get('regidium.agent.handler')->delete([ 'uid' => $uid ]);

        if ($result === 404) {
            return $this->view(['errors' => ['Agent not found!']]);
        } elseif ($result === 500) {
            return $this->view(['errors' => ['Server error!']]);
        }

        return $this->view(['success' => true]);
    }

    /**
     * Fetch a agent or throw an 404 Exception.
     *
     * @param array $criteria
     *
     * @return Agent
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404(array $criteria)
    {
        if (!($agent = $this->get('regidium.agent.handler')->one($criteria))) {
            throw new NotFoundHttpException(sprintf('The resource was not found.'));
        }

        return $agent;
    }
}
