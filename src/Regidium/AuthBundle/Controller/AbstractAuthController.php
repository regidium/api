<?php

namespace Regidium\AuthBundle\Controller;

use FOS\RestBundle\Controller\Annotations;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\AuthBundle\Document\Auth;
use Regidium\UserBundle\Document\User;
use Regidium\AgentBundle\Document\Agent;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Abstract authorization controller
 *
 * @package Regidium\AuthBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 *
 */
abstract class AbstractAuthController extends AbstractController
{

    protected function register($data, $remember = false) {
        $object = $this->get('regidium.user.handler')->post($data);

        if ($object instanceof User || $object instanceof Agent) {
            return $this->login($object, $remember);
        }

        return $this->view(['errors' => ['Error create user']]);
    }

    protected function login($object, $remember = false) {
        $session_max_age = $this->container->getParameter('session')['max_age'];
        $auths = $object->getAuths();
        $auth = null;
        if ($auths) {
            $auth = $auths->filter(function($a) use ($session_max_age) {
                    if ($a->getStarted() instanceof \MongoTimestamp) {
                        $started = $a->getStarted()->__toString();
                    } else {
                        $started = $a->getStarted()['sec'];
                    }
                    if ($a->getEnded() == null && $started + $session_max_age > time()) {
                        return true;
                    } else {
                        return false;
                    }
                })->last();
        }

        // Если нет активной сессии, тогда создаем её
        if (!$auth instanceof Auth) {
            $this->get('regidium.auth.handler')->post(
                $object,
                ['remember' => $remember]
            );
        } else {
            if ($auth->getRemember() == false) {
                $this->get('regidium.auth.handler')->put(
                    $auth,
                    ['remember' => $remember]
                );
            }
        }

        return $object;
    }

}
