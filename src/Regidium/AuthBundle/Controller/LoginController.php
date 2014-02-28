<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Person;

/**
 * Login controller
 *
 * @package Regidium\AuthBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Login")
 *
 */
class LoginController extends AbstractController
{
    /**
     * Авторизация персоны.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Login exist person from submitted data.",
     *   input = "Regidium\AuthBundle\Form\Login\LoginForm",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request Request объект
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        $email = $request->request->get('email', null);
        $password = $request->request->get('password', null);

        if (!$email || !$password) {
            return  $this->sendError('Login or password not valid');
        }

        $person = $this->get('regidium.person.handler')->one([
            'email' => $email,
            'password' => $password,
        ]);

        if (!$person instanceof Person) {
            return $this->sendError('User not found');
        }

        $return = $person->toArray();

        if ($person instanceof Person) {
            return $this->send($return);
        } else {
            return $this->sendError('Login service error!');
        }
    }

    /**
     * Получение информации о персоне.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Получение информации о персоне.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param string $uid UID персоны
     *
     * @return View
     */
    public function getCheckAction($uid)
    {
        /** @var Person $person */
        $person = $this->get('regidium.person.handler')->one([ 'uid' => $uid  ]);

        if($person instanceof Person) {
            $return = $person->toArray();

            return $this->send($return);
        } else {
            return $this->sendError('Person not found.');
        }
    }
}
