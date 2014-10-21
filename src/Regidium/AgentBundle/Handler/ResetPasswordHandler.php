<?php

namespace Regidium\AgentBundle\Handler;

use Regidium\CommonBundle\Document\ResetPasswordRequest;
use Regidium\CommonBundle\Handler\AbstractHandler;

class ResetPasswordHandler extends AbstractHandler
{
    /**
     * Создание новой сущности
     */
    public function post($agent)
    {
        $renew = $this->repository->findByAgent($agent);
        if ($renew instanceof ResetPasswordRequest){
            return $renew;
        }
        return $this->edit(new ResetPasswordRequest($agent));
    }

    /**
     * @param $token
     * @param $password
     *
     * @return array
     */
    public function renew($token, $password)
    {
        $resetPassword = $this->one(['secretCode' => $token]);

        if (!$resetPassword instanceof ResetPasswordRequest){
            return ['error' => 'Token not found'];
        }

        $agent = $resetPassword->getAgent();
        $agent->setPassword($password);

        $this->dm->remove($resetPassword);
        $this->dm->persist($agent);
        $this->dm->flush();

        return ['data' => $agent->toArray(['widget'])];
    }

    /**
     * Удаление
     *
     * @param $confirmation
     * @return bool|\Exception
     */
    public function delete($confirmation) {
        try {
            $this->dm->remove($confirmation);
            $this->dm->flush($confirmation);

            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Save edit
     *
     * @todo Remove
     *
     * @param ResetPasswordRequest $resetPassword
     *
     * @return ResetPasswordRequest
     */
    public function edit(ResetPasswordRequest $resetPassword) {
        $this->dm->persist($resetPassword);
        $this->dm->flush($resetPassword);

        return $resetPassword;
    }

}