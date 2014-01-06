<?php

namespace Regidium\AuthBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

use Regidium\AuthBundle\Form\Login\LoginForm;
use Regidium\AuthBundle\Form\Register\RegisterForm;
use Regidium\AuthBundle\Document\Auth;
use Regidium\UserBundle\Document\User;
use Regidium\AgentBundle\Document\Agent;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Authorization controller
 *
 * @todo Сделать абстракцию для всех контроллеров
 * @todo Обновление ключа авторизации
 * @todo Update response for HTML format
 *
 * @package Regidium\AuthBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Auth")
 *
 */
class AuthController extends FOSRestController
{

    /**
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned always"
     *   }
     * )
     *
     * @return bool
     */
    public function optionsAction()
    {
        return true;
    }


    /**
     * Check info about user or agent.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *   templateVar = "form"
     * )
     *
     * @param $uid
     *
     * @throws NotFoundHttpException
     * @return View
     */
    public function getCheckAction($uid)
    {
        $object = $this->get('regidium.user.handler')->one([ 'uid' => $uid  ]);
        if (!$object) {
            $object = $this->get('regidium.agent.handler')->one([ 'uid' => $uid  ]);
        }

        if($object instanceof User) {
            $view = new View([ 'user' => [
                "uid" => $object->getUid(),
                "fullname" => $object->getFullname()
            ]], Codes::HTTP_OK);
            return $this->handleView($view);
        } elseif ($object instanceof Agent) {
            $view = new View([ 'agent' => [
                "uid" => $object->getUid(),
                "fullname" => $object->getFullname()
            ]], Codes::HTTP_OK);
            return $this->handleView($view);
        } else {
            throw new NotFoundHttpException(sprintf('The resource was not found.'));
        }

        return $this->createForm(new RegisterForm());
    }

    /**
     * Presents the form to register a new user.
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
    public function getRegisterAction()
    {
        return $this->createForm(new RegisterForm());
    }

    /**
     * Register a user from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Register a user from the submitted data.",
     *   input = "Regidium\AuthBundle\Form\Register\RegisterForm",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *     template = "RegidiumAuthBundle:Register:index.html.twig",
     *     statusCode = Codes::HTTP_BAD_REQUEST,
     *     templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return View
     */
    public function postRegisterAction(Request $request)
    {
        $fullname = $request->request->get('fullname', null);
        $email = $request->request->get('email', null);
        $password = $request->request->get('password', null);
        $remember = $request->request->get('remember', false);

        if (!$email || !$password) {
            return  $this->view(['errors' => ['Login or password is null']]);
        }

        $user = $this->get('regidium.user.handler')->one([ 'email' => $email ]);

        $agent = null;
        if (!$user) {
            $agent = $this->get('regidium.agent.handler')->one([ 'email' => $email ]);
        }

        /** @todo Перевести ошибку */
        if ($user instanceof User || $agent instanceof Agent) {
            return  $this->view(['errors' => ['This email already registered!']]);
        }

        $object = $this->register([
            'fullname' => $fullname,
            'email' => $email,
            'password' => $password
        ], $remember);

        if ($object instanceof User) {
            $returnOptions = ['user' => $object];
        } elseif ($object instanceof Agent) {
            $returnOptions = ['agent' => $object];
        } else {
            $returnOptions = ['errors' => ['Register service error!']];
        }

        return $this->view($returnOptions, Codes::HTTP_CREATED);
    }

    /**
     * Presents the form to login exist user or agent.
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
    public function getLoginAction()
    {
        return $this->createForm(new LoginForm());
    }

    /**
     * Login exist user or agent from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Login exist user or agent from the submitted data.",
     *   input = "Regidium\AuthBundle\Form\Login\LoginForm",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template = "RegidiumAuthBundle:Login:index.html.twig",
     *   statusCode = Codes::HTTP_BAD_REQUEST,
     *   templateVar = "form"
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|View
     */
    public function postLoginAction(Request $request)
    {
        $email = $request->request->get('email', null);
        $password = $request->request->get('password', null);
        $remember = $request->request->get('remember', false);

        if (!$email || !$password) {
            return  $this->view(['errors' => ['Login or password is null']]);
        }

        $object = $this->get('regidium.user.handler')->one([
            'email' => $email,
            'password' => $password,
        ]);

        if (!$object) {
            $object = $this->get('regidium.agent.handler')->one([
                'email' => $email,
                'password' => $password,
            ]);
        }

        if (!$object instanceof User && !$object instanceof Agent) {
            return $this->view(['errors' => $object]);
        }

        $object = $this->login($object, $remember);
        if ($object instanceof User) {
            $returnOptions = ['user' => $object];
        } elseif ($object instanceof Agent) {
            $returnOptions = ['agent' => $object];
        } else {
            $returnOptions = ['errors' => ['Login service error!']];
        }

        return $this->view($returnOptions, Codes::HTTP_CREATED);
    }

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
    public function postExternalserviceConnectAction(Request $request, $provider)
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

            $object = $this->register($object);
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
     * Logout exist user or agent.
     *
     * @todo Create real logout
     *
     * @deprecated Will be updated
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param int   $uid    the user uid
     *
     * @return bool
     */
    public function postLogoutAction($uid)
    {
        $object = $this->get('regidium.user.handler')->one([ 'uid' => $uid ]);
        if (!$object) {
            $object = $this->get('regidium.agent.handler')->one([ 'uid' => $uid ]);
        }

        if ($object) {
            $this->get('regidium.auth.handler')->close($object);
            return true;
        } else {
            return false;
        }
    }

    private function register($data, $remember = false) {
        $object = $this->get('regidium.user.handler')->post($data);

        if ($object instanceof User || $object instanceof Agent) {
            return $this->login($object, $remember);
        }

        return $this->view(['errors' => ['Error create user']]);
    }

    private function login($object, $remember = false) {
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
