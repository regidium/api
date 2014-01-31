<?php

namespace Regidium\ChatBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\CommonBundle\Document\User;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\ChatMessage;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Chat message controller
 *
 * @todo Update response for HTML format
 * @todo Вынести всю работу с моделями в handlers
 *
 * @package Regidium\ChatBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Chat")
 */
class ChatMessageController extends AbstractController
{
    /**
     * Create a new chat message from the submitted data.
     *
     * @todo Сделать доступным через chat
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Creates a new chat message from the submitted data.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postMessageAction(Request $request)
    {
        $sender = null;
        $sender_uid = $request->request->get('sender', null);
        if ($sender_uid) {
            $sender = $this->get('regidium.user.handler')->one(['uid' => $sender_uid]);
            if (!$sender) {
                $sender = $this->get('regidium.agent.handler')->one(['uid' => $sender_uid]);
            }
        }

        if (!$sender instanceof User && $sender instanceof Agent) {
            return $this->view(['errors' => 'Sender not found!']);
        }

        $receiver = null;
        $receiver_uid = $request->request->get('receiver', null);
        if ($receiver_uid) {
            $receiver = $this->get('regidium.user.handler')->one(['uid' => $receiver_uid]);
            if (!$receiver) {
                $receiver = $this->get('regidium.agent.handler')->one(['uid' => $receiver_uid]);
            }
        }
        /*
         * Получаетель не обязателен
         *
        if (!$receiver instanceof User && $receiver instanceof Agent) {
            return $this->view(['errors' => 'Receiver not found!']);
        }
        */

        $chat_uid = $request->request->get('chat', null);
        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->view(['errors' => 'Chat not found!']);
        }

        $text = $request->request->get('text', null);
        if (!$text) {
            return $this->view(['errors' => 'Message is empty!']);
        }

        $result = $this->get('regidium.chat.message.handler')->post(
            $chat,
            $sender,
            $receiver,
            $text
        );

        if (!$result instanceof ChatMessage) {
            return $this->view(['errors' => $result]);
        }

        return $this->view($result);
    }
}