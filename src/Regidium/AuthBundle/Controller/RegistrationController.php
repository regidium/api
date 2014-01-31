<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\User;
use Regidium\CommonBundle\Document\Agent;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Registration controller
 *
 * @todo Обновление ключа авторизации
 * @todo Update response for HTML format
 * @todo Security
 *
 * @package Regidium\AuthBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Registration")
 *
 */
class RegistrationController extends AbstractAuthController
{
    /**
     * Registration person from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Registration person from the submitted data.",
     *   input = "Regidium\AuthBundle\Form\Registration\RegistrationForm",
     *   statusCodes = {
     *     200 = "Always Returned"
     *   }
     * )
     *
     * @param Request $request Request object
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        $fullname = $request->request->get('fullname', null);
        $email = $request->request->get('email', null);
        $password = $request->request->get('password', null);
        $remember = $request->request->get('remember', false);

        if (!$email || !$password) {
            return  $this->sendError('Login or password is null');
        }

        $person = $this->get('regidium.person.handler')->one([ 'email' => $email ]);

        if ($person instanceof Person) {
            return  $this->sendError('This email already registered!');
        }

        $person = $this->registration([
            'fullname' => $fullname,
            'email' => $email,
            'password' => $password
        ], $remember);

        if ($person instanceof Person) {
            return $this->send($person, Codes::HTTP_CREATED);
        } else {
            return $this->sendError('Registration error!');
        }
    }
}
