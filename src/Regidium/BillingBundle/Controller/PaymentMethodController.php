<?php

namespace Regidium\BillingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Regidium\CommonBundle\Controller\AbstractController;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Payment Method controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\BillingBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("PaymentMethod")
 */
class PaymentMethodController extends AbstractController
{
    /**
     * List all payment methods.
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
        $return = $this->get('regidium.billing.payment_method.handler')->all();

        return $this->view($return);
    }
}