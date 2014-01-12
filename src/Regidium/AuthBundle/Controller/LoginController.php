<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

use Regidium\AuthBundle\Form\Login\LoginForm;
use Regidium\UserBundle\Document\User;
use Regidium\AgentBundle\Document\Agent;

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
     * Presents the form to login exist user or agent.
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
        return $this->createForm(new LoginForm());
    }

    /**
     * Login exist user or agent from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Login exist user or agent from the submitted data.",
     *   input = "Regidium\AuthBundle\Form\Login\LoginForm",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template = "RegidiumAuthBundle:Login:index.html.twig",
     *   statusCode = Codes::HTTP_BAD_REQUEST,
     *   templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postAction(Request $request)
    {
        $email = $request->request->get('email', null);
        $password = $request->request->get('password', null);
        $remember = $request->request->get('remember', false);

        if (!$email || !$password) {
            return  $this->view(['errors' => ['Login or password is null']]);
        }

        $object = $this->get('regidium.user.handler')->one([
            'email' => $email,
            'password' => $password,
        ]);

        if (!$object) {
            $object = $this->get('regidium.agent.handler')->one([
                'email' => $email,
                'password' => $password,
            ]);
        }

        if (!$object instanceof User && !$object instanceof Agent) {
            return $this->view(['errors' => $object]);
        }

        $object = $this->login($object, $remember);
        if ($object instanceof User) {
            $returnOptions = ['user' => $object];
        } elseif ($object instanceof Agent) {
            $returnOptions = ['agent' => $object];
        } else {
            $returnOptions = ['errors' => ['Login service error!']];
        }

        return $this->view($returnOptions, Codes::HTTP_CREATED);
    }

    /**
     * Check info about user or agent.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *   templateVar = "form"
     * )
     *
     * @param $uid
     *
     * @throws NotFoundHttpException
     * @return View
     */
    public function getCheckAction($uid)
    {
        $object = $this->get('regidium.user.handler')->one([ 'uid' => $uid  ]);
        if (!$object) {
            $object = $this->get('regidium.agent.handler')->one([ 'uid' => $uid  ]);
        }

        if($object instanceof User) {
            $view = new View([ 'user' => [
                "uid" => $object->getUid(),
                "fullname" => $object->getFullname()
            ]], Codes::HTTP_OK);
            return $this->handleView($view);
        } elseif ($object instanceof Agent) {
            $view = new View([ 'agent' => [
                "uid" => $object->getUid(),
                "fullname" => $object->getFullname()
            ]], Codes::HTTP_OK);
            return $this->handleView($view);
        } else {
            throw new NotFoundHttpException(sprintf('The resource was not found.'));
        }
    }
}
