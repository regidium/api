<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

use Regidium\AuthBundle\Form\Register\RegisterForm;
use Regidium\UserBundle\Document\User;
use Regidium\AgentBundle\Document\Agent;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Register controller
 *
 * @todo Обновление ключа авторизации
 * @todo Update response for HTML format
 *
 * @package Regidium\AuthBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Register")
 *
 */
class RegisterController extends AbstractAuthController
{
    /**
     * Presents the form to register a new user.
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
        return $this->createForm(new RegisterForm());
    }

    /**
     * Register a user from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Register a user from the submitted data.",
     *   input = "Regidium\AuthBundle\Form\Register\RegisterForm",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *     template = "RegidiumAuthBundle:Register:index.html.twig",
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

        $object = $this->register([
            'fullname' => $fullname,
            'email' => $email,
            'password' => $password
        ], $remember);

        if ($object instanceof User) {
            $returnOptions = ['user' => $object];
        } elseif ($object instanceof Agent) {
            $returnOptions = ['agent' => $object];
        } else {
            $returnOptions = ['errors' => ['Register service error!']];
        }

        return $this->view($returnOptions, Codes::HTTP_CREATED);
    }
}
