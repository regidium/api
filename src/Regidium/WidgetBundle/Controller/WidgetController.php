<?php

namespace Regidium\WidgetBundle\Controller;

use Regidium\CommonBundle\Document\ChatMessage;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\Widget;

/**
 * Widget controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\WidgetBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Widget")
 */
class WidgetController extends AbstractController
{
    /**
     * Получение информации о виджете.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Получение информации о виджете.",
     *   output = "Regidium\CommonBundle\Document\Widget",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string $uid Widget UID
     *
     * @return array
     *
     */
    public function getAction(Request $request, $uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);

        if (!$widget instanceof Widget) {
            return $this->sendError('Widget resource was not found.');
        }

        $to = $request->request->getInt('to', 1);
        if ($to == 2) {
            return $this->send($widget->toArray(['triggers']));
        } else {
            return $this->send($widget->toArray(['plan', 'triggers']));
        }
    }

    /**
     * Получение списка новых сообщений.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Получение списка новых сообщений.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string $uid Widget UID
     *
     * @return array
     *
     */
    public function getMessagesNewAction($uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget was not found.');
        }

        $messages = [];
        /** @var Chat[] $chats */
        $chats = $widget->getChats();
        foreach($chats as $chat) {
//              $chat_messages = $chat->getMessages()->filter(function($e) {
//                  if($e->getReaded()) {
//                      return false;
//                  }
//
//                  return true;
//              })->map(function($e) {
//                  return [$e->getUid() => $e->toArray()];
//              });

            $chat_messages = $this->get('regidium.chat.message.repository')->createQueryBuilder()
                ->field('chat.id')->equals($chat->getId())
                ->field('readed')->equals(false)
                ->field('type')->notIn([ChatMessage::SENDER_TYPE_ROBOT_TO_AGENT, ChatMessage::SENDER_TYPE_ROBOT_TO_USER])
                ->getQuery()
                ->execute()
            ;
            foreach($chat_messages as $message) {
                $messages[$message->getUid()] = $message->toArray();
            }
        }

        return $this->sendArray($messages);
    }

    /**
     * Create a widget from the submitted data.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Creates a new widget from the submitted data.",
     *   input = "Regidium\WidgetBundle\Form\WidgetForm",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request Request object
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        $result = $this->get('regidium.widget.handler')->post(
            $request->request->all()
        );

        if (!$result instanceof Widget) {
            return $this->view(['errors' => $result]);
        }

        $routeOptions = [
            'uid' => $result->getUid(),
            '_format' => $request->get('_format')
        ];

        return $this->routeRedirectView('api_1_get_widget', $routeOptions, Codes::HTTP_CREATED);

    }

    /**
     * Update existing widget from the submitted data or create a new widget at a specific location.
     *
     * @ApiDoc(
     *   resource = false,
     *   input = "Regidium\WidgetBundle\Form\WidgetForm",
     *   statusCodes = {
     *     201 = "Returned when the widget is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @param Request $request Request object
     * @param string  $uid     Widget UID
     *
     * @return View
     */
    public function putAction(Request $request, $uid)
    {
        if (!($widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]))) {
            $statusCode = Codes::HTTP_CREATED;
            $post = [
                'email' => $request->request->get('email', null),
                'fullname' => $request->request->get('fullname', null),
                'password' => $request->request->get('password', null),
                'status' => $request->request->get('status', Widget::STATUS_DEFAULT)
            ];
            $widget = $this->get('regidium.widget.handler')->post(
                $post
            );
        } else {
            $statusCode = Codes::HTTP_OK;
            $password = $request->request->get('password', null);
            if ($widget->getPassword() != null && $password == null) {
                $password = $widget->getPassword();
            }
            $put = [
                'email' => $request->request->get('email', null),
                'fullname' => $request->request->get('fullname', null),
                'password' => $password,
                'status' => $request->request->get('status', 2)
            ];
            $widget = $this->get('regidium.widget.handler')->put(
                $widget,
                $put
            );
        }

        if (!$widget instanceof Widget) {
            return  $this->view(['errors' => $widget]);
        }

        $routeOptions = [
            'uid' => $widget->getUid(),
            '_format' => $request->get('_format')
        ];

        return $this->routeRedirectView('api_1_get_widget', $routeOptions, $statusCode);
    }

    /**
     * Remove existing widget.
     *
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string $uid Widget UID
     *
     * @return View
     *
     */
    public function deleteAction($uid)
    {
        $result = $this->get('regidium.widget.handler')->delete([ 'uid' => $uid ]);

        if ($result === 404) {
            return $this->view(['errors' => ['Widget not found!']]);
        } elseif ($result === 500) {
            return $this->view(['errors' => ['Server error!']]);
        }

        return $this->view(['success' => true]);
    }
}
