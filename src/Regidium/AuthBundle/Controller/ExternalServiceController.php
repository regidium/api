<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

use Regidium\UserBundle\Document\User;
use Regidium\AgentBundle\Document\Agent;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * External Service controller
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
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     *
     * @param Request $request the request object
     *
     * @param         $provider
     *
     * @return View
     */
    public function postAction(Request $request, $provider)
    {

        if (!in_array($provider, ['facebook', 'vkontakte', 'google', 'twitter'])) {
            return  $this->view(['errors' => ["The provider {$provider} was not found."]]);
        };

        $uid = $request->request->get('uid', null);
        $data = $request->request->get('data', []);
        $security = $request->request->get('security', null);

        /** @todo Проверка $data */
        $object = $this->get('regidium.user.handler')->oneByExternalService($provider, $data['id']);
        if (!$object) {
            $object = $this->get('regidium.agent.handler')->oneByExternalService($provider, $data['id']);
        }

        if ($object instanceof User || $object instanceof Agent) {
            if (isset($data['uid']) && $data['uid'] != $object->getUid()) {
                return $this->view(['errors' => ['External account already used']]);
            } else {
                if ($object instanceof User) {
                    $returnOptions = [
                        'user' => $this->login($object)
                    ];
                } elseif ($object instanceof Agent) {
                    $returnOptions = [
                        'agent' => $this->login($object)
                    ];
                }
            }
            return $this->view($returnOptions, Codes::HTTP_CREATED);
        } elseif($uid) {
            $object = $this->get('regidium.user.handler')->one(['uid' => $uid]);
            if (!$object) {
                $object = $this->get('regidium.agent.handler')->one(['uid' => $uid]);
            }
        } elseif (isset($data['email'])) {
            $object = $this->get('regidium.user.handler')->one(['email' => $data['email']]);
            if (!$object) {
                $object = $this->get('regidium.user.handler')->one(['email' => $data['email']]);
            }
        }

        $external_service[$provider] = [
            'provider' => $provider,
            'data' => $data,
            'security' => $security
        ];

        if ($object) {
            $object->setExternalService($external_service);
            if ($object instanceof User) {
                $this->get('regidium.user.handler')->edit($object);
                $returnOptions = [
                    'user' => $object
                ];
            } else {
                $this->get('regidium.agent.handler')->edit($object);
                $returnOptions = [
                    'agent' => $object
                ];
            }
        } else {
            $object = array();
            if (isset($data['fullname'])) $object['fullname'] = $data['fullname'];
            if (isset($data['email'])) $object['email'] = $data['email'];

            $object = $this->registration($object);
            if ($object instanceof User || $object instanceof Agent) {
                $object->setExternalService($external_service);
            }

            if ($object instanceof User) {
                $this->get('regidium.user.handler')->edit($object);
                $returnOptions = [
                    'user' => $object
                ];
            } elseif ($object instanceof Agent) {
                $this->get('regidium.agent.handler')->edit($object);
                $returnOptions = [
                    'agent' => $object
                ];
            } else {
                $returnOptions = [ 'errors' => [ 'Error connect external service!' ] ];
            }
        }

        return $this->view($returnOptions, Codes::HTTP_CREATED);
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
