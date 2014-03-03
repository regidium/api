<?php

namespace Regidium\BillingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;

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
     * Получение списка всех методов оплаты.
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Получение списка всех методов оплаты.",
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
