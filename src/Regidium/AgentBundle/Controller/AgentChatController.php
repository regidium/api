<?php

namespace Regidium\AgentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\CommonBundle\Document\Agent;
use Regidium\CommonBundle\Document\Chat;

/**
 * Agent chat controller
 *
 * @todo Update response for HTML format
 * @todo Security
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
     *   description = "List all agent chats.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $uid Agent uid
     *
     * @return View
     */
    public function cgetAction($uid)
    {
        $agent = $this->get('regidium.agent.handler')->one(['uid' => $uid]);

        if (!$agent instanceof Agent) {
            return $this->send(['errors' => ['Agent not found!']]);
        }

        return $this->send($agent->getChats());
    }

    /**
     * Добавлям агента к чату.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Добавлям агента к чату.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param int $uid  Agent UID
     * @param int $chat_uid Chat UID
     *
     * @return View
     */
    public function putAction($uid, $chat_uid)
    {
        /** @var Agent $agent */
        $agent = $this->get('regidium.agent.handler')->one(['uid' => $uid]);
        if (!$agent instanceof Agent) {
            return $this->sendError('Agent not found!');
        }

        /** @var Chat $chat */
        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $chat->setOperator($agent);
        $return = $this->get('regidium.chat.handler')->edit($chat);
        if (!$return instanceof Chat) {
            return $this->sendError('Server error!', Codes::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->send($return);
    }
}
