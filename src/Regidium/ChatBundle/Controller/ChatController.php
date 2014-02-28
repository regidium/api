<?php

namespace Regidium\ChatBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Chat;

/**
 * Chat controller
 *
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
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string $uid UID чата
     *
     * @return array
     */
    public function getAction($uid)
    {
        $chat = $this->get('regidium.chat.handler')->one(['uid' => $uid]);
        if (!$chat instanceof Chat) {
            return $this->sendError('Chat not found!');
        }

        return $this->getOr404($chat);
    }
}
