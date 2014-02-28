<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Person;

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
     * Авторизация персоны через внешние сервисы.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Авторизация персоны через внешние сервисы.",
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

        if (!in_array($provider, ['facebook', 'vkontakte', 'google', 'twitter'])) {
            return  $this->sendError('The provider '.$provider.' was not found.');
        };

        $uid = $request->request->get('uid', null);
        $data = $request->request->get('data', []);
        $security = $request->request->get('security', null);

        $person = $this->get('regidium.person.handler')->oneByExternalService($provider, $data['id']);

        if ($person instanceof Person) {
            if (isset($data['uid']) && $data['uid'] != $person->getUid()) {
                return $this->sendError('External account already used');
            } else {
                return $this->send($person->toArray());
            }
        } elseif($uid) {
            $person = $this->get('regidium.person.handler')->one(['uid' => $uid]);
        } elseif (isset($data['email'])) {
            $person = $this->get('regidium.person.handler')->one(['email' => $data['email']]);
        }

        $external_service[$provider] = [
            'provider' => $provider,
            'data' => $data,
            'security' => $security
        ];

        if ($person) {
            $person->setExternalService($external_service);
            $person = $this->get('regidium.person.handler')->edit($person);
            return $this->send($person);
        } else {
            $person_data = [];
            if (isset($data['fullname'])) {
                $person_data['fullname'] = $data['fullname'];
            }

            if (isset($data['email'])) {
                $person_data['email'] = $data['email'];
            }

            $person = $this->get('regidium.person.handler')->post($person_data);

            if ($person instanceof Person) {
                $person->setExternalService($external_service);
                $person = $this->get('regidium.person.handler')->edit($person);

                return $this->send($person->toArray());
            } else {
                return $this->sendError('Error connect external service!');
            }
        }
    }

    /**
     * Отключение внешнего сервиса персоны.
     *
     * @todo Не реализовано
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Отключение внешнего сервиса персоны.",
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
