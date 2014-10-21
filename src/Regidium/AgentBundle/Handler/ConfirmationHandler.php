<?php

namespace Regidium\AgentBundle\Handler;

use Regidium\CommonBundle\Document\Confirmation;
use Regidium\CommonBundle\Handler\AbstractHandler;
use Regidium\CommonBundle\Document\Agent;

class ConfirmationHandler extends AbstractHandler
{
    /**
     * Создание новой сущности
     *
     * @return string|array|Agent
     */
    public function post(Agent $agent)
    {
        return $this->edit(new Confirmation($agent));
    }

    /**
     * Активация агента
     *
     * @param $secretCode
     * @return array
     */
    public function confirm($secretCode)
    {
        $confirmation = $this->one(['secretCode' => $secretCode]);

        if (!$confirmation instanceof Confirmation){
            return ['error' => 'code not found'];
        }

        $agent = $confirmation->getAgent();
        $agent->setActive(Agent::STATUS_ACTIVATED);
        $this->dm->persist($agent);
        $this->dm->remove($confirmation);
        $this->dm->flush();

        return $agent->toArray();
    }

    /**
     * Удаление агента
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
     * Save edit Agent
     *
     * @todo Remove
     *
     * @param Confirmation $confirmation
     *
     * @return Confirmation
     */
    public function edit(Confirmation $confirmation) {
        $this->dm->persist($confirmation);
        $this->dm->flush($confirmation);
        return $confirmation;
    }

}