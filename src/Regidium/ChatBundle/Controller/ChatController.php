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
