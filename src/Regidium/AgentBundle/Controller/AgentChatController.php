<?php

namespace Regidium\AgentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\AgentBundle\Document\Agent;
use Regidium\ChatBundle\Document\Chat;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Agent chat controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\AgentBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Chat")
 */
class AgentChatController extends AbstractController
{
    /**
     * List all agent chats.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $uid  Agent uid
     *
     * @return View
     */
    public function cgetAction($uid)
    {
        $agent = $this->get('regidium.agent.handler')->one(['uid' => $uid]);

        if (!$agent instanceof Agent) {
            return $this->view(['errors' => ['Agent not found!']]);
        }

        return $this->view($agent->getChats());
    }

    /**
     * Update existing chat.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when has errors"
     *   }
     * )
     *
     * @todo Update
     *
     * @param int  $uid   Agent uid
     * @param int  $chat  Chat uid
     *
     * @return View
     */
    public function putAction($uid, $chat)
    {
        $agent = $this->get('regidium.agent.handler')->one(['uid' => $uid]);
        if (!$agent instanceof Agent) {
            return $this->view(['errors' => ['Agent not found!']]);
        }

        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat]);
        if (!$chat instanceof Chat) {
            return $this->view(['errors' => ['Chat not found!']]);
        }

        $chat->setAgent($agent);
        $return = $this->get('regidium.chat.handler')->edit($chat);
        if (!$return instanceof Chat) {
            return $this->view(['errors' => ['Server error!']]);
        }

        return $this->view($return);
    }
}
