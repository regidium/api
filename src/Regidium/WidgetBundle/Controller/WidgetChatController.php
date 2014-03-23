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
 * @package Regidium\UserBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Chat")
 */
class WidgetChatController extends AbstractController
{
    /**
     * Получаем список чатов
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Получаем список архивных чатов.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string  $uid       Widget UID
     *
     * @return View
     *
     */
    public function cgetExistedAction($uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        /** @var Chat[] $chats */
        $chats = $this->get('regidium.chat.handler')->get(['widget.id' => $widget->getId()]);
        $return = [];
        foreach ($chats as $chat) {
            $c = [];
            $c['chat'] = $chat->toArray();
            $c['person'] = $chat->getUser()->getPerson(['user'])->toArray();
            $return[] = $c;
        }

        return  $this->sendArray($return);
    }

    /**
     * Получаем список чатов с состоянием В чате
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Получаем список чатов с состоянием В чате.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string  $uid       Widget UID
     *
     * @return View
     *
     */
    public function cgetOnlineAction($uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        /** @var Chat[] $chats */
        $chats = $this->get('regidium.chat.handler')->get(['widget.id' => $widget->getId(), 'status' => Chat::STATUS_CHATTING]);
        $return = [];
        foreach ($chats as $chat) {
            $c = [];
            $c['chat'] = $chat->toArray();
            $c['person'] = $chat->getUser()->getPerson(['user'])->toArray();
            $return[] = $c;
        }

        return  $this->sendArray($return);
    }

    /**
     * Получаем список архивных чатов
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Получаем список архивных чатов.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string  $uid       Widget UID
     *
     * @return View
     *
     */
    public function cgetArchiveAction($uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        /** @var Chat[] $chats */
        $chats = $this->get('regidium.chat.handler')->get(['widget.id' => $widget->getId(), 'messages.archived' => true]);

        return  $this->sendArray($chats);
    }

    /**
     * Создаем новый чат для виджета
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Создаем новый чат для виджета.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request   Request object
     * @param string  $uid       Widget UID
     *
     * @return View
     *
     */
    public function postAction(Request $request, $uid)
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

        $person = $this->get('regidium.person.handler')->one(['uid' => $request->get('person_uid', null)]);
        if (!$person instanceof Person) {
            return $this->sendError('User not found!');
        }

        $data = $request->request->all();
        $data['widget_uid'] = $widget->getUid();
        $data['user_uid'] = $person->getUser()->getUid();

        $chat = $this->get('regidium.chat.handler')->post($data);

        if (!$chat instanceof Chat) {
            return $this->sendError($chat);
        }

        $return = [
            'uid' => $chat->getUid()
        ];

        return  $this->send($return);
    }

    /**
     * Подключение чата
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Подключение чата.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
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
    public function putOnlineAction(Request $request, $uid, $chat_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $this->get('regidium.chat.handler')->online($chat);

        return $this->sendSuccess();
    }

    /**
     * Отключение чата
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Отключение чата.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
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
    public function putOfflineAction(Request $request, $uid, $chat_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $this->get('regidium.chat.handler')->offline($chat);

        return $this->sendSuccess();
    }

    /**
     * Создаем новое сообщение для чата.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Создаем новое сообщение для чата.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
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

        $data = $request->request->all();
        $data['chat_uid'] = $chat->getUid();

        $chat_message = $this->get('regidium.chat.message.handler')->post($data);
        $this->get('regidium.chat.handler')->chatting($chat);

        if (!$chat_message instanceof ChatMessage) {
            return $this->sendError($chat_message);
        }

        $return = [
            'uid' => $chat_message->getUid(),
            'chat' => $chat_message->getChat()->toArray()
        ];

        return $this->send($return);
    }
}