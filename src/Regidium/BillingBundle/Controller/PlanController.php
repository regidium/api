<?php

namespace Regidium\BillingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;

/**
 * Plan controller
 *
 * @todo Security
 *
 * @package Regidium\BillingBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Plan")
 */
class PlanController extends AbstractController
{
    /**
     * Получение списка всех планов.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Получение списка всех планов.",
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request Request объект
     *
     * @return array
     */
    public function cgetAction(Request $request)
    {
        $payment_plans = $this->get('regidium.billing.plan.handler')->all();

        $return = [];
        foreach($payment_plans as $payment_plan) {
            $return[] = $payment_plan->toArray();
        }

        return $this->sendArray($return);
    }
}
