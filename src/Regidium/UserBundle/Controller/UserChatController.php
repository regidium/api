<?php

namespace Regidium\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\UserBundle\Document\User;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
     * List all user chats.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request  $uid  User uid
     *
     * @return array
     */
    public function cgetAction($uid)
    {
        $user = $this->get('regidium.user.handler')->one(['uid' => $uid]);

        if (!$user instanceof User) {
            return $this->view(['errors' => ['User not found!']]);
        }

        return $this->view($user->getChats());
    }
}
