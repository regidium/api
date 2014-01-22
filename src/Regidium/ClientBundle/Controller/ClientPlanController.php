<?php

namespace Regidium\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\ClientBundle\Document\Client;
use Regidium\BillingBundle\Document\Plan;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Client plan controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\ClientBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Plan")
 */
class ClientPlanController extends AbstractController
{
    /**
     * Select client plan.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when has errors"
     *   }
     * )
     *
     * @param Request $request
     * @param string  $uid  Client uid
     * @param string  $plan Plan uid
     *
     * @return View
     */
    public function putAction(Request $request, $uid, $plan)
    {
        $client = $this->get('regidium.client.handler')->one(['uid' => $uid]);
        if (!$client instanceof Client) {
            return $this->view(['errors' => ['Client not found!']]);
        }

        $plan = $this->get('regidium.billing.plan.handler')->one(['uid' => $plan]);
        if (!$plan instanceof Plan) {
            return $this->view(['errors' => ['Plan not found!']]);
        }

        if ($client->getBalance() < $plan->getCost()) {
            return $this->view(['errors' => ['Not enough money!']]);
        }

        $client->setBalance($client->getBalance() - $plan->getCost());
        $client->setAvailableAgents($client->getAvailableAgents() + $plan->getCountAgents());
        $client->setAvailableChats($client->getAvailableChats() + $plan->getCountChats());
        $this->get('regidium.client.handler')->edit($client);

        return $this->view($client);
    }
}
