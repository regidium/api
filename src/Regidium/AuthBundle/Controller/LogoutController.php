<?php

namespace Regidium\AuthBundle\Controller;

use FOS\RestBundle\Controller\Annotations;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
class LogoutController extends AbstractAuthController
{
    /**
     * Logout exist user or agent.
     *
     * @todo Create real logout
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Logout exist user or agent.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param string $uid Person UID
     *
     * @return bool
     */
    public function getAction($uid)
    {
        $person = $this->get('regidium.person.handler')->one([ 'uid' => $uid ]);

        if ($person) {
            $this->get('regidium.auth.handler')->close($person);
            return true;
        } else {
            return false;
        }
    }
}
