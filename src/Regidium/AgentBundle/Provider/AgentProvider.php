<?php
/**
 * @author Russell Kvashnin <russell.kvashnin@gmail.com>
 */
namespace Regidium\AgentBundle\Provider;


use Documents\Agent;
use Regidium\AgentBundle\Handler\AgentHandler;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AgentProvider implements UserProviderInterface {

    private $agentHandler;

    public function __construct(AgentHandler $agentHandler)
    {
        $this->agentHandler = $agentHandler;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     */
    public function loadUserByUsername($email)
    {
        $agent = $this->agentHandler->one(['email' => $email]);

        if (!$agent){
            return $exception = ['error' => 'Agent not found'];
        }

        return $agent;
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     * @param UserInterface $user
     * @return UserInterface
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        return;
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return;
    }
}