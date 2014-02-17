<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;

use Regidium\CommonBundle\Document\Person;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Login controller
 *
 * @package Regidium\AuthBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Login")
 *
 */
class LoginController extends AbstractAuthController
{
    /**
     * Login exist person from submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Login exist person from submitted data.",
     *   input = "Regidium\AuthBundle\Form\Login\LoginForm",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request Request object
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        $email = $request->request->get('email', null);
        $password = $request->request->get('password', null);
        $remember = $request->request->get('remember', false);

        if (!$email || !$password) {
            return  $this->sendError('Login or password is null');
        }

        $person = $this->get('regidium.person.handler')->one([
            'email' => $email,
            'password' => $password,
        ]);

        if (!$person instanceof Person) {
            return $this->sendError('User not found');
        }

        $person = $this->login($person, $remember);

        $return = [
            'uid' => $person->getUid(),
            'fullname' => $person->getFullname(),
            'avatar' => $person->getAvatar(),
            'email' => $person->getEmail(),
            'model_type' => $person->getModelType(),
            'agent' => [
                'uid' => $person->getAgent()->getUid(),
                'model_type' => $person->getAgent()->getModelType(),
                'job_title' => $person->getAgent()->getJobTitle(),
                'widget' => [
                    'uid' => $person->getAgent()->getWidget()->getUid()
                ]
            ]
        ];

        if ($person instanceof Person) {
            return $this->send($return);
        } else {
            return $this->sendError('Login service error!');
        }
    }

    /**
     * Check info about person.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Check info about user or agent.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param string $uid Person UID
     *
     * @return View
     */
    public function getCheckAction($uid)
    {
        /** @var Person $person */
        $person = $this->get('regidium.person.handler')->one([ 'uid' => $uid  ]);

        $return = [
            'uid' => $person->getUid(),
            'fullname' => $person->getFullname(),
            'avatar' => $person->getAvatar(),
            'email' => $person->getEmail(),
            'model_type' => $person->getModelType(),
            'agent' => [
                'uid' => $person->getAgent()->getUid(),
                'model_type' => $person->getAgent()->getModelType(),
                'job_title' => $person->getAgent()->getJobTitle(),
                'widget' => [
                    'uid' => $person->getAgent()->getWidget()->getUid()
                ]
            ]
        ];

        if($person instanceof Person) {
            return $this->send($return);
        } else {
            $this->sendError('The resource was not found.');
        }
    }
}
