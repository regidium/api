<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Agent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
     * Авторизация агента.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Авторизация агента.",
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

        $agent = $this->get('regidium.agent.handler')->one([
            'email' => $email,
            'password' => $password,
        ]);

        if (!$agent instanceof Agent) {
            return $this->sendArray(['error' => 'Agent not found']);
        }

        if ($agent->getActive() === Agent::STATUS_NOT_ACTIVATED) {
            return $this->sendArray(['error' => 'Agent not activated']);
        }

//         Записываем последний визит агента
        $data = $this->prepareAgentSessionData($request);

        $this->get('regidium.agent.handler')->online($agent, $data);

        $return = [
            'agent' => $agent->toArray(['widget'])
        ];

        return $this->send($return);
    }

    /**
     * Напоминание пароля агента.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Напоминание пароля агента.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request Request объект
     *
     * @return View
     */
    public function postForgotAction(Request $request)
    {
        $email = $request->request->get('email', null);
        if (!$email) {
            return  $this->sendError('Login not valid');
        }

        $agent = $this->get('regidium.agent.handler')->one([
            'email' => $email
        ]);

        if (!$agent instanceof Agent) {
            return $this->sendArray(['error' => 'Agent not found']);
        }

        $resetPasswordRequest = $this->get('regidium.reset_password.handler')->post($agent);

        $this->get('regidium.mail.handler')->post([
            'receivers' => [$agent->getEmail()],
            'title' => 'Reset password request',
            'template' => 'RegidiumMailBundle:Agent/Notification:password_reset.html.twig',
            'data' => [
                'resetPassword' => $resetPasswordRequest->toArray(),
                'agent' => $agent->toArray()
            ]
        ]);

        return $this->send(['data' => 'success']);
    }

    /**
     * Получение информации об агенте.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Получение информации об агенте.",
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
        /** @var Agent $agent */
        $agent = $this->get('regidium.agent.handler')->one([ 'uid' => $uid  ]);

        if(!$agent instanceof Agent) {
            return $this->sendError('Agent not found.');
        }

        $return = [
            'agent' => $agent->toArray(['widget'])
        ];

        return $this->send($return);
    }

    /**
     * Подготовка данных об агенте из пришедших данных
     *
     * @param Request $request  Request объект
     *
     * @return array
     */
    protected function prepareAgentSessionData(Request $request)
    {
        return [
            'country' => strval($request->request->get('country', null)),
            'city' => strval($request->request->get('city', null)),
            'ip' => strval($request->request->get('ip', null)),
            'device' => strval($request->request->get('device', null)),
            'os' => strval($request->request->get('os', null)),
            'browser' => strval($request->request->get('browser', null)),
            'language' => strval($request->request->get('language', null))
        ];
    }
}
