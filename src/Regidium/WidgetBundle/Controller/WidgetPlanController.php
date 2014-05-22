<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Plan;

/**
 * Widget plan controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\WidgetBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Plan")
 */
class WidgetPlanController extends AbstractController
{
    /**
     * Select widget plan.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when has errors"
     *   }
     * )
     *
     * @param Request $request Request объект
     * @param string  $uid     UID виджета
     *
     * @return View
     */
    public function putAction(Request $request, $uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $plan_type = $request->request->get('plan', null);

        $plan = $this->get('regidium.billing.plan.handler')->one(['type' => (int)$plan_type]);
        if (!$plan instanceof Plan) {
            return $this->sendError('Plan not found!');
        }

        if ($widget->getBalance() < $plan->getCost()) {
            return $this->sendError('Not enough money!');
        }

        $widget->setPlan($plan);
        $widget->setBalance($widget->getBalance() - $plan->getCost());
        $widget->setAvailableAgents($plan->getCountAgents());
        $this->get('regidium.widget.handler')->edit($widget);

        return $this->sendSuccess();
    }
}
