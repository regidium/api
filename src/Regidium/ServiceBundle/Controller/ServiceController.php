<?php

namespace Regidium\ServiceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;

/**
 * Service controller
 *
 * @todo Security
 *
 * @package Regidium\ServiceBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Service")
 */
class ServiceController extends AbstractController
{
    /**
     * Отключение повисших пользователей (после перезагрузки).
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Отключение повисших пользователей (после перезагрузки).",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="chats_uids", nullable=true, description="Массив UID активных")
     *
     * @param Request $request Request объект
     *
     * @return array
     */
    public function putDisconnectUsersAction(Request $request)
    {
        $chats_uids = $request->get('chats_uids', []);

        $this->get('regidium.service.handler')->disconnect($chats_uids);

        return $this->sendSuccess();
    }
}
