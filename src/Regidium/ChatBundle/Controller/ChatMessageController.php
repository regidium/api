<?php

namespace Regidium\ChatBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\CommonBundle\Document\Person;
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
        $sender = $this->get('regidium.person.handler')->one(['uid' => $request->request->get('sender', null)]);
        if (!$sender instanceof Person) {
            return $this->sendError('Sender not found!');
        }

        $receiver = null;
        $receiver_uid = $request->request->get('receiver', null);
        if ($receiver_uid) {
            $receiver = $this->get('regidium.person.handler')->one(['uid' => $receiver_uid]);
        }
        /*
         * Получаетель не обязателен
         *
        if (!$receiver instanceof Person) {
            return $this->sendError('Receiver not found!');
        }
        */

        $chat_uid = $request->request->get('chat', null);
        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $text = $request->request->get('text', null);
        if (!$text) {
            return $this->sendError('Message is empty!');
        }

        $result = $this->get('regidium.chat.message.handler')->post(
            $chat,
            $sender,
            $receiver,
            $text
        );

        if (!$result instanceof ChatMessage) {
            return $this->sendError($result);
        }

        return $this->send($result);
    }
}