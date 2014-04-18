<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\CommonBundle\Document\Agent;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\ChatMessage;
use Regidium\CommonBundle\Document\Widget;

/**
 * Widget Chat controller
 *
 * @package Regidium\CommonBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Chat")
 */
class WidgetChatController extends AbstractController
{
    /**
     * Получаем список существующих чатов
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Получаем список существующих чатов.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string  $uid       Widget UID
     * @param string  $agent_uid  Agent UID
     *
     * @return View
     *
     */
    public function cgetExistedAction($uid, $agent_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $agent = $this->get('regidium.agent.handler')->one(['uid' => $agent_uid]);
        if (!$agent instanceof Agent) {
            return $this->sendError('Agent not found!');
        }

        $where = ['widget.id' => $widget->getId()];

        if ($agent->getRenderVisitorsPeriod() == Agent::RENDER_VISITORS_PERIOD_DAY) {
            /** @todo Добавлять день */
            $where['ended_at'] = ['$gte' => $agent->getLastVisit()];
        } elseif ($agent->getRenderVisitorsPeriod() == Agent::RENDER_VISITORS_PERIOD_WEEK) {
            /** @todo Добавлять неделю */
            $where['ended_at'] = ['$gte' => $agent->getLastVisit()];
        } else {
            $where['ended_at'] = ['$gte' => $agent->getLastVisit()];
        }

        /** @var Chat[] $chats */
        $chats = $this->get('regidium.chat.handler')->get($where);
        $return = [];
        foreach ($chats as $chat) {
            $return[] = $chat->toArray(['messages']);
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
            $return[] = $chat->toArray(['messages']);
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

        $return = [];
        foreach ($chats as $chat) {
            $return[] = $chat->toArray(['messages']);
        }

        return  $this->sendArray($return);
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

        $data = $request->request->all();
        $data['widget_uid'] = $widget->getUid();

        $chat = $this->get('regidium.chat.handler')->post($data);
        if (!$chat instanceof Chat) {
            return $this->sendError($chat);
        }

        return  $this->send($chat->toArray(['messages']));
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

        $chat->setSocketId($request->get('socket_id'));

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
     * В Чате
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "В Чате.",
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
    public function putChattingAction(Request $request, $uid, $chat_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $chat->setSocketId($request->get('socket_id'));

        $this->get('regidium.chat.handler')->chatting($chat);

        return $this->sendSuccess();
    }

    /**
     * Пользователь ввел авторизационные данные
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Пользователь ввел авторизационные данные.",
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
    public function putAuthAction(Request $request, $uid, $chat_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $data = [
            'first_name' => $request->request->get('first_name', ''),
            'email' => $request->request->get('email', '')
        ];

        $this->get('regidium.chat.handler')->auth($chat, $data);

        return $this->sendSuccess();
    }


    /**
     * Агент подключился к чату
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Агент подключился к чату.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request   Request object
     * @param string  $uid       Widget UID
     * @param string  $chat_uid  Chat UID
     * @param string  $agent_uid  Agent UID
     *
     * @return View
     *
     */
    public function putAgentAction(Request $request, $uid, $chat_uid, $agent_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $agent = $this->get('regidium.agent.handler')->one(['uid' => $agent_uid]);
        if (!$agent instanceof Agent) {
            return $this->sendError('Agent not found!');
        }

        $chat = $this->get('regidium.chat.handler')->agentEnter($chat, $agent);

        return $this->send($chat->toArray(['agent', 'messages']));
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

        return $this->send($chat_message->toArray());
    }

    private function prepareUserData(Request $request)
    {
        /** @todo Получать IP для proxy */
        return [
            'first_name' => $request->get('first_name', null),
            'last_name' => $request->get('last_name', null),
            'email' => $request->get('email', null),
            'country' => $request->get('country', null),
            'city' => $request->get('city', null),
            'ip' => $request->get('ip', null),
            'device' => $request->get('device', null),
            'os' => $request->get('os', null),
            'browser' => $request->get('browser', null),
            'keyword' => $request->get('keyword', null),
            'language' => $request->get('language', 'ru')
        ];
    }
}