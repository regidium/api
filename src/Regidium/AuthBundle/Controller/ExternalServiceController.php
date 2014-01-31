<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

use Regidium\CommonBundle\Document\User;
use Regidium\CommonBundle\Document\Agent;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
class ExternalServiceController extends AbstractAuthController
{
    /**
     * Login exist user or agent from external service.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Login exist user or agent from external service.",
     *   statusCodes = {
     *     200 = "Always Returned"
     *   }
     * )
     *
     * @param Request $request Request object
     *
     * @param $provider
     *
     * @return View
     */
    public function postAction(Request $request, $provider)
    {

        if (!in_array($provider, ['facebook', 'vkontakte', 'google', 'twitter'])) {
            return  $this->sendError("The provider {$provider} was not found.");
        };

        $uid = $request->request->get('uid', null);
        $data = $request->request->get('data', []);
        $security = $request->request->get('security', null);

        $person = $this->get('regidium.person.handler')->oneByExternalService($provider, $data['id']);

        if ($person instanceof Person) {
            if (isset($data['uid']) && $data['uid'] != $person->getUid()) {
                return $this->sendError('External account already used');
            } else {
                return $this->send($this->login($person));
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
            $person = array();
            if (isset($data['fullname'])) {
                $person['fullname'] = $data['fullname'];
            }

            if (isset($data['email'])) {
                $person['email'] = $data['email'];
            }

            $person = $this->registration($person);
            if ($person instanceof Person) {
                $person->setExternalService($external_service);
                $person = $this->get('regidium.person.handler')->edit($person);
                return $this->send($person, Codes::HTTP_CREATED);
            } else {
                return $this->sendError('Error connect external service!');
            }
        }
    }

    /**
     * Disconnect external service.
     *
     * @todo Create real disconnect
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Disconnect external service.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @return bool
     */
    public function deleteAction()
    {
        return true;
    }
}
