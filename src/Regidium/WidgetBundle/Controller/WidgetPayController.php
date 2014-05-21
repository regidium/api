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
     * Создание оплаты виджета.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @todo Update
     *
     * @param Request $request
     * @param string  $uid Widget UID
     *
     * @return View
     */
    public function postAction(Request $request, $uid)
    {
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $payment_method_type = $request->request->get('payment_method', null);

        $payment_method = $this->get('regidium.billing.payment_method.handler')->one(['type' => (int)$payment_method_type]);
        if (!$payment_method instanceof PaymentMethod) {
            return $this->sendError('Payment method not found!');
        }

        $amount = $request->request->get('amount', 0);
        if ($amount <= 0) {
            return $this->sendError('Amount error!');
        }

        $payment = $this->get('regidium.billing.payment.handler')->post(
            $widget,
            $payment_method,
            $amount
        );

        if (!$payment instanceof Payment) {
            return $this->sendError('Payment save error!');
        }

        /** @todo Запись действий */
        $widget->setBalance($widget->getBalance() + $amount);
        $this->get('regidium.widget.handler')->edit($widget);

        return $this->send($payment->toArray(['payment_method']));
    }
}
