<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Payment;
use Regidium\CommonBundle\Document\PaymentMethod;

/**
 * Widget payment controller
 *
 * @package Regidium\WidgetBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Pay")
 */
class WidgetPayController extends AbstractController
{
    /**
     * Create widget payment.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when has errors"
     *   }
     * )
     *
     * @todo Update
     *
     * @param Request $request
     * @param string  $uid Widget UID
     * @param int  $payment_method
     *
     * @return View
     */
    public function postAction(Request $request, $uid, $payment_method)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $payment_method = $this->get('regidium.billing.payment_method.handler')->one(['type' => (int)$payment_method]);
        if (!$payment_method instanceof PaymentMethod) {
            return $this->view(['errors' => ['Payment method not found!']]);
        }

        $amount = $request->request->get('amount', 0);
        if ($amount <= 0) {
            return $this->view(['errors' => ['Amount error!']]);
        }

        $payment = $this->get('regidium.billing.payment.handler')->post(
            $widget,
            $payment_method,
            $amount
        );

        if (!$payment instanceof Payment) {
            return $this->view(['errors' => ['Payment save error!']]);
        }

        /** @todo Запись дейсвий */
        $widget->setBalance($widget->getBalance() + $amount);
        $this->get('regidium.widget.handler')->edit($widget);

        return $this->view($payment);
    }
}
