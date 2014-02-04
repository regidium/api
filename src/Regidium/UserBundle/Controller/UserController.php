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
 * @todo Update response for HTML format
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
     * List all users.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "List all users",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing users.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many users to return.")
     *
     * @return array
     */
    public function cgetAction()
    {
        $users = $this->get('regidium.user.handler')->all();

        return $this->sendArray(array_values($users));
    }

    /**
     * Get single user.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Gets a users for a given uid",
     *   output = "Regidium\CommonBundle\Document\User",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param string $uid User UID
     *
     * @return array
     */
    public function getAction($uid)
    {
        $user = $this->getOr404(['uid' => $uid]);

        return $this->send($user);
    }

    /**
     * Создаем пользователя
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Create new user from the submitted data.",
     *   input = "Regidium\UserBundle\Form\UserForm",
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
        $person = $this->get('regidium.user.handler')->post($this->prepareUserData($request, $request->request->get('password', null)));

        if (!$person instanceof Person) {
            return $this->returnError($person);
        }

        return $this->send($person, Codes::HTTP_CREATED);

    }

    /**
     * Update existing user from submitted data or create new user at a specific location.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Update existing user from submitted data or create new user at a specific location.",
     *   input = "Regidium\UserBundle\Form\UserForm",
     *   statusCodes = {
     *     201 = "Returned when user is created",
     *     200 = "Returned when user is updated",
     *   }
     * )
     *
     * @param Request $request Request object
     * @param string  $uid     User UID
     *
     * @return View
     *
     */
    public function putAction(Request $request, $uid)
    {
        $person = $this->get('regidium.person.handler')->one(['uid' => $uid]);

        if (!$person) {
            $statusCode = Codes::HTTP_CREATED;

            $person = $this->get('regidium.user.handler')->post(
                $this->prepareUserData($request, $request->request->get('password', null))
            );
        } else {
            $statusCode = Codes::HTTP_OK;

            $password = $request->request->get('password', null);
            if ($person->getPassword() != null && $password == null) {
                $password = $person->getPassword();
            }

            $person = $this->get('regidium.user.handler')->put(
                $person->getUser(),
                $this->prepareUserData($request, $password)
            );
        }

        if (!$person instanceof Person) {
            return $this->sendError($person);
        }

        return  $this->send($person, $statusCode);
    }

    /**
     * Remove existing user.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Remove existing user.",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *   }
     * )
     *
     * @param string $uid User UID
     *
     * @return View
     *
     */
    public function deleteAction($uid)
    {
        $result = $this->get('regidium.user.handler')->delete([ 'uid' => $uid ]);

        if ($result === 404) {
            return $this->returnError('User not found!');
        } elseif ($result === 500) {
            return $this->returnError('Server error!', Codes::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->returnSuccess();
    }

    /**
     * Fetch a user or 404.
     *
     * @param array $criteria Criteria for search
     *
     * @return User|int
     */
    protected function getOr404(array $criteria)
    {
        $user = $this->get('regidium.user.handler')->one($criteria);

        if (!$user) {
            return $this->returnError('The resource was not found.');
        }

        return $user;
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
