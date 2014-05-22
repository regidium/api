<?php

namespace Regidium\AuthBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Agent;

/**
 * Logout controller
 *
 * @todo Security
 *
 * @package Regidium\AuthBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Logout")
 *
 */
class LogoutController extends AbstractController
{
    /**
     * Выход агента из системы.
     *
     * @todo Не реализовано
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Выход агента из системы.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string $uid UID персоны
     *
     * @return View
     */
    public function getAction($uid)
    {
        $agent = $this->get('regidium.agent.handler')->one([ 'uid' => $uid ]);

        if ($agent instanceof Agent) {
            return $this->send(true);
        } else {
            return $this->sendError($agent);
        }
    }
}