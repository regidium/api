<?php

namespace Regidium\AuthBundle\Controller;

use FOS\RestBundle\Controller\Annotations;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Logout controller
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
     * @deprecated Will be updated
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param int   $uid    the user uid
     *
     * @return bool
     */
    public function getAction($uid)
    {
        $object = $this->get('regidium.user.handler')->one([ 'uid' => $uid ]);
        if (!$object) {
            $object = $this->get('regidium.agent.handler')->one([ 'uid' => $uid ]);
        }

        if ($object) {
            $this->get('regidium.auth.handler')->close($object);
            return true;
        } else {
            return false;
        }
    }
}
