<?php

namespace Regidium\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Chat;
use Regidium\CommonBundle\Document\User;

/**
 * User chat controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\UserBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Chat")
 */
class UserChatController extends AbstractController
{
    /**
     * Получение списка чатов пользователя.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request  $uid  User uid
     *
     * @return View
     */
    public function cgetAction($uid)
    {
        $user = $this->get('regidium.user.handler')->one(['uid' => $uid]);

        if (!$user instanceof User) {
            return $this->sendError('User not found!');
        }

        return $this->sendArray(array_values($user->getChats()));
    }


    /**
     * Create a new chat from submitted data.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Creates a chat user from submitted data.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     *
     * @param Request $request request object
     * @param string $uid User UID
     *
     * @return View
     */
    public function postAction(Request $request, $uid)
    {
        $widget = null;
        if (!isset($_SERVER['HTTP_ORIGIN'])) {
            return $this->sendError('Widget not found!');
        }

        $widget = $this->get('regidium.widget.handler')->one(['url' => new \MongoRegex("/{$_SERVER['HTTP_ORIGIN']}$/")]);
        if (!$widget) {
            return $this->sendError('Widget not found!');
        }

        $user = $this->get('regidium.user.handler')->one(['uid' => $uid]);

        if (!$user instanceof User) {
            return $this->sendError('User not found!');
        }

        $data = $request->request->all();
        $data['widget_uid'] = $widget->getUid();
        $data['user_uid'] = $user->getUid();

        $chat = $this->get('regidium.chat.handler')->post($data);

        if (!$chat instanceof Chat) {
            return $this->sendError($chat);
        }

        return $this->send($chat);
    }
}
