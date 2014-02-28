<?php

namespace Regidium\AuthBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Person;

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
     * Выход персоны из системы.
     *
     * @todo Не реализовано
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Выход персоны из системы.",
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
        $person = $this->get('regidium.person.handler')->one([ 'uid' => $uid ]);

        if ($person instanceof Person) {
            return $this->send(true);
        } else {
            return $this->sendError($person);
        }
    }
}