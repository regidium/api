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

        if ($agent->getRenderVisitorsPeriod() == Agent::RENDER_VISITORS_PERIOD_SESSION) {
            $where['$or'] = [
                ['ended_at' => ['$exists' => false]],
                ['ended_at' => ['$gte' => $agent->getLastVisit()]],
            ];
        } elseif ($agent->getRenderVisitorsPeriod() == Agent::RENDER_VISITORS_PERIOD_DAY) {
            $where['$or'] = [
                ['ended_at' => ['$exists' => false]],
                ['ended_at' => ['$gte' => strtotime('+1 day', $agent->getLastVisit())]],
            ];
        } elseif ($agent->getRenderVisitorsPeriod() == Agent::RENDER_VISITORS_PERIOD_WEEK) {
            $where['$or'] = [
                ['ended_at' => ['$exists' => false]],
                ['ended_at' => ['$gte' => strtotime('+1 weeks', $agent->getLastVisit())]],
            ];
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

        // @todo Временная мера
        /** @var Chat[] $chats */
        //$chats = $this->get('regidium.chat.handler')->get(['widget.id' => $widget->getId(), 'status' => Chat::STATUS_CHATTING]);
        $chats = $this->get('regidium.chat.repository')->createQueryBuilder()
            ->field('widget.id')->equals($widget->getId())
            ->field('opened')->equals(true)
            //->field('messages')->exists(true)
            //->field('messages')->size(false)
            ->getQuery()
            ->execute()
        ;

        $return = [];
        foreach ($chats as $chat) {
            if ($chat->getMessages()->count()) {
                $return[] = $chat->toArray(['messages']);
            }
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
     * Редактирование существующего чата
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Редактирование существующего чата.",
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
    public function patchAction(Request $request, $uid, $chat_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $data = $request->request->get('chat', []);

        if ($data) {
            $chat = $this->get('regidium.chat.handler')->patch($chat, $data);
        }

        if (!$chat instanceof Chat) {
            return $this->sendError('Server error!');
        }

        return $this->sendSuccess();
    }


    /**
     * Смена URL чата
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Смена URL чата.",
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
    public function patchUrlAction(Request $request, $uid, $chat_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $current_url = $request->request->get('current_url', '');

        $this->get('regidium.chat.handler')->changeUrl($chat, $current_url);

        return $this->sendSuccess();
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

        $this->get('regidium.chat.handler')->chatting($chat);

        return $this->sendSuccess();
    }

    /**
     * Закрытие чата
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
    public function putClosedAction(Request $request, $uid, $chat_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $this->get('regidium.chat.handler')->closed($chat);

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
     * Агент отключился от чата
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Агент отключился от чата.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request   Request object
     * @param string  $uid       Widget UID
     * @param string  $chat_uid  Chat UID
     * @param string  $agent_uid Agent UID
     *
     * @return View
     *
     * @todo Логировать действия
     *
     */
    public function deleteAgentAction(Request $request, $uid, $chat_uid, $agent_uid)
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

        $chat = $this->get('regidium.chat.handler')->agentLeave($chat);
        if (!$chat instanceof Chat) {
            return $this->sendError('Server error!');
        }

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
        if (!$chat_message instanceof ChatMessage) {
            return $this->sendError($chat_message);
        }

        $this->get('regidium.chat.handler')->chatting($chat);

        return $this->send($chat_message->toArray());
    }
    

    /**
     * Прочтение сообщения чата.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Прочтение сообщения чата.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request           Request object
     * @param string  $uid               Widget UID
     * @param string  $chat_uid          Chat UID
     * @param string  $chat_message_uid  Chat Message UID
     *
     * @return View
     *
     */
    public function putMessageReadAction(Request $request, $uid, $chat_uid, $chat_message_uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $chat = $this->get('regidium.chat.handler')->one(['uid' => $chat_uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        $chat_message_uid = $this->get('regidium.chat.message.handler')->one(['uid' => $chat_message_uid]);
        if (!$chat_message_uid instanceof ChatMessage) {
            return $this->sendError('Chat message not found!');
        }

        $this->get('regidium.chat.message.handler')->read($chat_message_uid);

        return $this->sendSuccess();
    }
}