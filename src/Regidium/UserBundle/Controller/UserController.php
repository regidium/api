<?php

namespace Regidium\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\User;

/**
 * User controller
 *
 * @todo Security
 *
 * @package Regidium\UserBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("User")
 */
class UserController extends AbstractController
{
    /**
     * Получение информации о пользователе.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Получение информации о пользователе",
     *   output = "Regidium\CommonBundle\Document\User",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string $uid UID пользователя
     *
     * @return View
     */
    public function getAction($uid)
    {
        $user = $this->get('regidium.user.handler')->one(['uid' => $uid]);

        if (!$user) {
            return $this->returnError('The resource was not found.');
        }

        return $this->view($user->toArray());
    }


    protected function prepareUserData(Request $request, $password)
    {
        return [
            'fullname' => $request->request->get('fullname', null),
            'avatar' => $request->request->get('avatar', null),
            'email' => $request->request->get('email', null),
            'password' => $password,
            'status' => $request->request->get('status', User::STATUS_DEFAULT),
            'country' => $request->request->get('country', null),
            'city' => $request->request->get('city', null),
            'ip' => $request->request->get('ip', null),
            'device' => $request->request->get('device', null),
            'os' => $request->request->get('os', null),
            'browser' => $request->request->get('browser', null),
            'keyword' => $request->request->get('keyword', null),
            'language' => $request->request->get('language', 'ru')
        ];
    }
}
