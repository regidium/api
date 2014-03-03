<?php

namespace Regidium\BillingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;

/**
 * Plan controller
 *
 * @todo Update response for HTML format
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
     * @Annotations\View(
     *   templateVar="plans",
     *   statusCode=200
     * )
     *
     * @param Request $request Request объект
     *
     * @return array
     */
    public function cgetAction(Request $request)
    {
        $return = $this->get('regidium.billing.plan.handler')->all();

        return $this->view($return);
    }
}
