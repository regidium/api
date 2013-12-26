<?php

namespace Regidium\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class UserController extends FOSRestController
{
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
    public function getUsersAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        return $this->container->get('regidium_user.user.handler')->all();
    }

    /**
     * Get single user.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a users for a given id",
     *   output = "Regidium\UserBundle\Document\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="user")
     *
     * @param int     $id      the user id
     *
     * @return array
     *
     * @throws NotFoundHttpException when user not exist
     */
    public function getUserAction($id)
    {
        $user = $this->getOr404($id);

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
    public function newUserAction()
    {
        return $this->createForm(new UserType());
    }

    /**
     * Create a user from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new user from the submitted data.",
     *   input = "Regidium\UserBundle\Form\Type\UserType",
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
    public function postUserAction(Request $request)
    {
        try {
            $newUser = $this->container->get('regidium_user.user.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newUser->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_user', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }
    }

    /**
     * Update existing user from the submitted data or create a new user at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Regidium\UserBundle\Form\Type\UserType",
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
     * @param Request $request the request object
     * @param int     $id      the user id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when user not exist
     */
    public function putUserAction(Request $request, $id)
    {
        try {
            if (!($user = $this->container->get('regidium_user.user.handler')->get($id))) {
                $statusCode = Codes::HTTP_CREATED;
                $user = $this->container->get('regidium_user.user.handler')->post(
                    $request->request->all()
                );
            } else {
                $statusCode = Codes::HTTP_NO_CONTENT;
                $user = $this->container->get('regidium_user.user.handler')->put(
                    $user,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'id' => $user->getId(),
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
     * @param Request $request the request object
     * @param int     $id      the user id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when user not exist
     */
    public function patchUserAction(Request $request, $id)
    {
        try {
            $user = $this->container->get('regidium_user.user.handler')->patch(
                $this->getOr404($id),
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $user->getId(),
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
     * @param mixed $id
     *
     * @return User
     *
     * @throws NotFoundHttpException
     */
    protected function getOr404($id)
    {
        if (!($user = $this->container->get('regidium_user.user.handler')->get($id))) {
            throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
        }

        return $user;
    }
}
