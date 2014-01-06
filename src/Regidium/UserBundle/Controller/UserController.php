<?php

namespace Regidium\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Regidium\UserBundle\Form\UserForm;
use Regidium\UserBundle\Document\User;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * User controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\UserBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("User")
 */
class UserController extends FOSRestController
{

    /**
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Always returned"
     *   }
     * )
     *
     * @return bool
     */
    public function optionsAction()
    {
        return true;
    }

    /**
     * List all users.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing users.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many users to return.")
     *
     * @Annotations\View(
     *  templateVar="users"
     * )
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getAllAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $return = [];
        $users = $this->get('regidium.user.handler')->all();
        foreach ($users as $user) {
            $return[] = [
                'uid' => $user->getUid(),
                'fullname' => $user->getFullname(),
                'email' => $user->getEmail(),
                'state' => $user->getState()
            ];
        }
        return $return;
    }

    /**
     * Get single user.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a users for a given uid",
     *   output = "Regidium\UserBundle\Document\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="user")
     *
     * @param int     $uid      the user uid
     *
     * @return array
     *
     * @throws NotFoundHttpException when user not exist
     */
    public function getAction($uid)
    {
        $user = $this->getOr404(['uid' => $uid]);

        return $user;
    }

    /**
     * Presents the form to use to create a new user.
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
    public function newAction()
    {
        return $this->createForm(new UserForm());
    }

    /**
     * Create a user from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new user from the submitted data.",
     *   input = "Regidium\UserBundle\Form\UserForm",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "RegidiumUserBundle:User:newUser.html.twig",
     *  statusCode = Codes::HTTP_BAD_REQUEST,
     *  templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postAction(Request $request)
    {
        $result = $this->get('regidium.user.handler')->post(
            $request->request->all()
        );

        if (!$result instanceof User) {
            return $this->view(['errors' => $result]);
        }

        $routeOptions = array(
            'uid' => $result->getUid(),
            '_format' => $request->get('_format')
        );

        return $this->routeRedirectView('api_1_get_user', $routeOptions, Codes::HTTP_CREATED);

    }

    /**
     * Update existing user from the submitted data or create a new user at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Regidium\UserBundle\Form\UserForm",
     *   statusCodes = {
     *     201 = "Returned when the user is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "RegidiumUserBundle:User:editUser.html.twig",
     *  templateVar = "form"
     * )
     *
     * @todo Update
     *
     * @deprecated Will be updated
     *
     * @param Request $request the request object
     * @param int     $uid      the user uid
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when user not exist
     */
    public function putAction(Request $request, $uid)
    {
        try {
            if (!($user = $this->get('regidium.user.handler')->get(['uid' => $uid]))) {
                $statusCode = Codes::HTTP_CREATED;
                $user = $this->get('regidium.user.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $user = $this->get('regidium.user.handler')->put(
                    $user,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'uid' => $user->getUid(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_user', $routeOptions, $statusCode);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing user from the submitted data or create a new user at a specific location.
     *
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Regidium\UserBundle\Form\Type\UserType",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *  template = "RegidiumUserBundle:User:editUser.html.twig",
     *  templateVar = "form"
     * )
     *
     * @todo Remove
     *
     * @deprecated Will be removed
     *
     * @param Request $request the request object
     * @param int     $uid      the user uid
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when user not exist
     */
    public function patchAction(Request $request, $uid)
    {
        try {
            $user = $this->get('regidium.user.handler')->patch(
                $this->getOr404(['uid' => $uid]),
                $request->request->all()
            );

            $routeOptions = array(
                'uid' => $user->getUid(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_user', $routeOptions, Codes::HTTP_NO_CONTENT);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Fetch a user or throw an 404 Exception.
     *
     * @param array $criteria
     *
     * @return User
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404(array $criteria)
    {
        if (!($user = $this->get('regidium.user.handler')->one($criteria))) {
            throw new NotFoundHttpException(sprintf('The resource was not found.'));
        }

        return $user;
    }
}
