<?php

namespace Regidium\BillingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Regidium\CommonBundle\Controller\AbstractController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

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
     * List all plans.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View(
     *   templateVar="plans",
     *   statusCode=200
     * )
     *
     * @param Request $request request object
     *
     * @return array
     */
    public function cgetAction(Request $request)
    {
        $return = $this->get('regidium.billing.plan.handler')->all();

        return $this->view($return);
    }
}
