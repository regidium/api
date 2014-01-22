<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

use Regidium\AuthBundle\Form\Registration\RegistrationForm;
use Regidium\UserBundle\Document\User;
use Regidium\AgentBundle\Document\Agent;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Registration controller
 *
 * @todo Обновление ключа авторизации
 * @todo Update response for HTML format
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
     * Presents the form to registration a new user.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *  templateVar = "form"
     * )
     *
     * @return FormTypeInterface
     */
    public function getAction()
    {
        return $this->createForm(new RegistrationForm());
    }

    /**
     * Registration a user from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Registration a user from the submitted data.",
     *   input = "Regidium\AuthBundle\Form\Registration\RegistrationForm",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *     template = "RegidiumAuthBundle:Registration:index.html.twig",
     *     statusCode = Codes::HTTP_BAD_REQUEST,
     *     templateVar = "form"
     * )
     *
     * @param Request $request the request object
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
            return  $this->view(['errors' => ['Login or password is null']]);
        }

        $user = $this->get('regidium.user.handler')->one([ 'email' => $email ]);

        $agent = null;
        if (!$user) {
            $agent = $this->get('regidium.agent.handler')->one([ 'email' => $email ]);
        }

        if ($user instanceof User || $agent instanceof Agent) {
            return  $this->view(['errors' => ['This email already registered!']]);
        }

        $object = $this->registration([
            'fullname' => $fullname,
            'email' => $email,
            'password' => $password
        ], $remember);

        if ($object instanceof User) {
            $returnOptions = ['user' => $object];
        } elseif ($object instanceof Agent) {
            $returnOptions = ['agent' => $object];
        } else {
            $returnOptions = ['errors' => ['Registration service error!']];
        }

        return $this->view($returnOptions, Codes::HTTP_CREATED);
    }
}
