<?php

namespace Regidium\BillingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;

/**
 * Payment Method controller
 *
 * @todo Security
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
        $payment_methods = $this->get('regidium.billing.payment_method.handler')->all();

        $return = [];
        foreach($payment_methods as $payment_method) {
            $return[] = $payment_method->toArray();
        }

        return $this->sendArray($return);
    }
}
