<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Agent;

/**
 * External Service controller
 *
 * @todo Security
 *
 * @package Regidium\AuthBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Externalservice")
 *
 */
class ExternalServiceController extends AbstractController
{
    /**
     * Авторизация агента через внешние сервисы.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Авторизация агента через внешние сервисы.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request  Request объект
     * @param string  $provider Провайдер авторизации
     *
     * @return View
     */
    public function postAction(Request $request, $provider)
    {
        /** @todo Вынести в конфиг */
        if (!in_array($provider, ['facebook', 'vkontakte', 'google', 'twitter'])) {
            return  $this->sendError('The provider '.$provider.' was not found.');
        };

        $uid = $request->request->get('uid', null);
        $data = $request->request->get('data', []);
        $security = $request->request->get('security', null);

        $agent = $this->get('regidium.agent.handler')->oneByExternalService($provider, $data['id']);

        if ($agent instanceof Agent) {
            if (isset($data['uid']) && $data['uid'] != $agent->getUid()) {
                return $this->sendError('External account already used');
            } else {
                return $this->send($agent->toArray());
            }
        } elseif($uid) {
            $agent = $this->get('regidium.agent.handler')->one(['uid' => $uid]);
        } elseif (isset($data['email'])) {
            $agent = $this->get('regidium.agent.handler')->one(['email' => $data['email']]);
        }

        $external_service[$provider] = [
            'provider' => $provider,
            'data' => $data,
            'security' => $security
        ];

        if ($agent) {
            $agent->setExternalService($external_service);
            // Записываем последний визит агента
            $agent->setLastVisit(time());
            $agent = $this->get('regidium.agent.handler')->edit($agent);
            return $this->send($agent);
        } else {
            $agent_data = [];
            if (isset($data['fullname'])) {
                $agent_data['fullname'] = $data['fullname'];
            }

            if (isset($data['email'])) {
                $agent_data['email'] = $data['email'];
            }

            $agent = $this->get('regidium.agent.handler')->post($agent_data);

            if ($agent instanceof Agent) {
                $agent->setExternalService($external_service);
                $agent = $this->get('regidium.agent.handler')->edit($agent);

                return $this->send($agent->toArray());
            } else {
                return $this->sendError('Error connect external service!');
            }
        }
    }

    /**
     * Отключение внешнего сервиса агента.
     *
     * @todo Не реализовано
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Отключение внешнего сервиса агента.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @return View
     */
    public function deleteAction()
    {
        return $this->send(true);
    }
}
