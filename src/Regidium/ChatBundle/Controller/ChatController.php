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
     * Get single chat.
     *
     * @todo Сделать доступным через пользователя
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Gets chat for a given uid",
     *   output = "Regidium\ChatBundle\Document\Chat",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the chat is not found"
     *   }
     * )
     *
     *
     * @Annotations\View(templateVar="chat")
     *
     * @param int     $uid   chat uid
     *
     * @return array
     *
     * @throws NotFoundHttpException when chat not exist
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
        $client = null;
        if (!isset($_SERVER['HTTP_ORIGIN'])) {
            return $this->view(['errors' => ['Client not found!']]);
        }

        $client = $this->get('regidium.client.handler')->one(['url' => $_SERVER['HTTP_ORIGIN']]);
        if (!$client) {
            return $this->view(['errors' => ['Client not found!']]);
        }

        if ($client->getAvailableChats() < 1) {
            return $this->view(['errors' => ['Client is not available to create new chat!']]);
        }

        $user = $this->get('regidium.user.handler')->one(['uid' => $request->request->get('user', null)]);

        if (!$user instanceof User) {
            return $this->view(['errors' => 'User not found! ']);
        }

        $result = $this->get('regidium.chat.handler')->post(
            $client,
            $user,
            $request->request->all()
        );

        if (!$result instanceof Chat) {
            return $this->view(['errors' => $result]);
        }

        $client->setAvailableChats($client->getAvailableChats() - 1);
        $this->get('regidium.client.handler')->edit($client);

        return $this->view($result);
    }

    /**
     * Fetch a chat or throw an 404 Exception.
     *
     * @param array $criteria
     *
     * @return Chat
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404(array $criteria)
    {
        if (!($chat = $this->get('regidium.chat.handler')->one($criteria))) {
            throw new NotFoundHttpException(sprintf('The resource was not found.'));
        }

        return $chat;
    }
}
