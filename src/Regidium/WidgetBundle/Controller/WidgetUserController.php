<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Person;
use Regidium\CommonBundle\Document\User;
use Regidium\CommonBundle\Document\Chat;

/**
 * Widget User controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\UserBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("User")
 */
class WidgetUserController extends AbstractController
{
    /**
     * List all users in widget.
     *
     * @todo Проверять возможность просмотра для пользователя
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "List all users in widget",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $uid Widget UID
     *
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing users.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many users to return.")
     *
     * @return View
     */
    public function cgetAction($uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);

        /** @todo вернуть ошибку */
        if (!$widget instanceof Widget) {
            return $this->sendArray([]);
        }

        return $this->sendArray($widget->getUsers()->getValues());
    }

    /**
     * Создаем нового пользователя для виджета
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Создаем нового пользователя для виджета.",
     *   input = "Regidium\UserBundle\Form\UserForm",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request Request object
     * @param Request $uid     Widget UID
     *
     * @return View
     */
    public function postAction(Request $request, $uid)
    {
        // Получаем виджет
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);

        // Возвращаем ошибку если виджет не найден
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        // Создаем пользователя
        $person = $this->get('regidium.user.handler')->post($widget, $this->prepareUserData($request, $request->get('password', null)));

        // Возвращаем ошибку если пользователь не создан
        if (!$person instanceof Person) {
            return $this->sendError($person);
        }

        $return = [
            'uid' => $person->getUid(),
            'model_type' => $person->getModelType(),
            'fullname' => $person->getFullname(),
            'avatar' => $person->getAvatar(),
            'email' => $person->getEmail(),
            'status' => $person->getStatus(),
            'country' => $person->getCountry(),
            'city' => $person->getCity(),
            'ip' => $person->getIp(),
            'os' => $person->getOs(),
            'device' => $person->getDevice(),
            'browser' => $person->getBrowser(),
            'user_uid' => $person->getUser()->getUid()
        ];

        return $this->send($return, Codes::HTTP_CREATED);
    }

    /**
     * Обновляем существующего или создаем нового пользователя для виджета.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Обновляем существующего или создаем нового пользователя для виджета.",
     *   input = "Regidium\AgentBundle\Form\UserForm",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request   Request object
     * @param string  $uid       Widget UID
     * @param string  $user_uid  User UID
     *
     * @return View
     *
     */
    public function putAction(Request $request, $uid, $user_uid = null)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);

        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $user = null;
        if ($user_uid) {
            $user = $this->get('regidium.user.handler')->one(['uid' => $user_uid]);
        }

        if (!$user) {
            $statusCode = Codes::HTTP_OK;

            $person = $this->get('regidium.user.handler')->post(
                $widget,
                $this->prepareUserData($request, $request->request->get('password', null))
            );
        } else {
            $statusCode = Codes::HTTP_OK;

            $password = $request->request->get('password', null);
            if ($user->getPerson()->getPassword() != null && $password == null) {
                $password = $user->getPerson()->getPassword();
            }

            $person = $this->get('regidium.user.handler')->put(
                $user,
                $this->prepareUserData($request, $password)
            );
        }

        if (!$person instanceof Person) {
            return $this->sendError($person);
        }

        $return = [
            'uid' => $person->getUid(),
            'model_type' => $person->getModelType(),
            'fullname' => $person->getFullname(),
            'avatar' => $person->getAvatar(),
            'email' => $person->getEmail(),
            'status' => $person->getStatus(),
            'country' => $person->getCountry(),
            'city' => $person->getCity(),
            'ip' => $person->getIp(),
            'os' => $person->getOs(),
            'device' => $person->getDevice(),
            'browser' => $person->getBrowser(),
            'user_uid' => $person->getUser()->getUid()
        ];

        return  $this->send($return, $statusCode);
    }

    /**
     * Создаем новый чат для виджета.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Создаем новый чат для виджета.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $request   Request object
     * @param string  $uid       Widget UID
     * @param string  $user_uid  User UID
     *
     * @return View
     *
     */
    public function postChatAction(Request $request, $uid, $user_uid)
    {
        /* @todo дополнительная проверка URL запроса
        if (!isset($_SERVER['HTTP_ORIGIN'])) {
        return $this->sendError('Widget not found!');
        }
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid, 'url' => new \MongoRegex("/{$_SERVER['HTTP_ORIGIN']}$/")]);
         */

        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $user = $this->get('regidium.user.handler')->one(['uid' => $user_uid]);
        if (!$user instanceof User) {
            return $this->sendError('User not found!');
        }

        $chat = $this->get('regidium.chat.handler')->post(
            $widget,
            $user,
            $request->request->all()
        );

        if (!$chat instanceof Chat) {
            return $this->sendError($chat);
        }

        return  $this->send($chat);
    }

    private function prepareUserData(Request $request, $password)
    {
        /** @todo Получать IP для proxy */
        return [
            'fullname' => $request->get('fullname', null),
            'avatar' => $request->get('avatar', null),
            'email' => $request->get('email', null),
            'password' => $password,
            'status' => $request->get('status', User::STATUS_DEFAULT),
            'country' => $request->get('country', null),
            'city' => $request->get('city', null),
            'ip' => $request->getClientIp(),
            'device' => $request->get('device', null),
            'os' => $request->get('os', null),
            'browser' => $request->get('browser', null),
            'keyword' => $request->get('keyword', null),
            'language' => $request->get('language', 'ru')
        ];
    }
}