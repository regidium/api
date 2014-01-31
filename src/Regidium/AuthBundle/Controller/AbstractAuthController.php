<?php

namespace Regidium\AuthBundle\Controller;

use FOS\RestBundle\Controller\Annotations;

use Regidium\CommonBundle\Controller\AbstractController;

use Regidium\CommonBundle\Document\Auth;
use Regidium\CommonBundle\Document\Person;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Abstract authorization controller
 *
 * @package Regidium\AuthBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 */
abstract class AbstractAuthController extends AbstractController
{

    /**
     * Registration person
     *
     * @param array $data Data about person
     * @param bool $remember Remember user session
     *
     * @return Person|int
     */
    protected function registration($data, $remember = false) {
        $person = $this->get('regidium.person.handler')->post($data);

        if ($person instanceof Person) {
            return $this->login($person, $remember);
        }

        return $this->sendError(500);
    }

    /**
     * Login person
     *
     * @param Person $person Person to login
     * @param bool $remember Remember user session
     *
     * @return Person
     */
    protected function login($person, $remember = false) {
        $session_max_age = $this->container->getParameter('session')['max_age'];
        $auths = $person->getAuths();
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
                $person,
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

        return $person;
    }

}
