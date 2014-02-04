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

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Chat controller
 *
 * @todo Update response for HTML format
 * @todo Вынести всю работу с моделями в handlers
 *
 * @package Regidium\ChatBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Chat")
 */
class ChatController extends AbstractController
{
    /**
     * Получить информацию о чате.
     *
     * @todo Сделать доступным через пользователя
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Получить информацию о чате.",
     *   output = "Regidium\CommonBundle\Document\Chat",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param int     $uid   chat uid
     *
     * @return array
     */
    public function getAction($uid)
    {
        return $this->getOr404(['uid' => $uid]);
    }

    /**
     * Create a new chat from the submitted data.
     *
     * @todo Сделать доступным через пользователя
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Creates a chat user from the submitted data.",
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
    public function postAction(Request $request)
    {
        $widget = null;
        if (!isset($_SERVER['HTTP_ORIGIN'])) {
            return $this->sendError('Widget not found!');
        }

        $widget = $this->get('regidium.widget.handler')->one(['url' => new \MongoRegex("/{$_SERVER['HTTP_ORIGIN']}$/")]);
        if (!$widget) {
            return $this->sendError('Widget not found!');
        }

        if ($widget->getAvailableChats() < 1) {
            return $this->sendError('Widget is not available to create new chat!');
        }

        $user = $this->get('regidium.user.handler')->one(['uid' => $request->request->get('user', null)]);

        if (!$user instanceof User) {
            return $this->sendError('User not found!');
        }

        $result = $this->get('regidium.chat.handler')->post(
            $widget,
            $user,
            $request->request->all()
        );

        if (!$result instanceof Chat) {
            return $this->sendError($result);
        }

        $widget->setAvailableChats($widget->getAvailableChats() - 1);
        $this->get('regidium.widget.handler')->edit($widget);

        return $this->send($result);
    }

    /**
     * Fetch a chat or throw an 404 Exception.
     *
     * @param array $criteria
     *
     * @return string|Chat
     */
    protected function getOr404(array $criteria)
    {
        if (!($chat = $this->get('regidium.chat.handler')->one($criteria))) {
            return $this->sendError('The resource was not found.');
        }

        return $chat;
    }
}
