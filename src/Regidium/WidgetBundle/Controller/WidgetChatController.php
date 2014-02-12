<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\ChatMessage;

/**
 * Widget Chat controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\UserBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Chat")
 */
class WidgetChatController extends AbstractController
{
    /**
     * Создаем новое сообщение для чата.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Создаем новое сообщение для чата.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request   Request object
     * @param string  $uid       Widget UID
     * @param string  $chat_uid  Chat UID
     *
     * @return View
     *
     */
    public function postMessageAction(Request $request, $uid, $chat_uid)
    {
        /* @todo дополнительная проверка URL запроса
        
        if (!isset($_SERVER['HTTP_ORIGIN'])) {
            return $this->sendError('Widget not found!');
        }

        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid, 'url' => new \MongoRegex("/{$_SERVER['HTTP_ORIGIN']}$/", 'uid' => $uid)]);
        */

        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $sender = $this->get('regidium.person.handler')->one(['uid' => $request->request->get('sender', null)]);
        if (!$sender instanceof Person) {
            return $this->sendError('Sender not found!');
        }

        $receiver = $this->get('regidium.person.handler')->one(['uid' => $request->request->get('sender', null)]);
/*        if (!$receiver instanceof Person) {
            return $this->sendError('Receiver not found!');
        }*/

        $chat_message = $this->get('regidium.chat.message.handler')->post(
            $chat,
            $sender,
            $receiver,
            $request->request->get('text', '')
        );

        if (!$chat_message instanceof ChatMessage) {
            return $this->sendError($chat_message);
        }

        return  $this->send($chat_message);
    }
}