<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Transaction;

/**
 * Widget transaction controller
 *
 * @package Regidium\WidgetBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Transaction")
 */
class WidgetTransactionController extends AbstractController
{
    /**
     * Создание транзакции оплаты виджета.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
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

        $data = $request->request->get('payment', []);
        $data['widget_uid'] = $widget->getUid();

        $payment_params = $this->container->getParameter('payment');
        $data['receiver'] = $payment_params['client_id'];

        $transaction = $this->get('regidium.billing.transaction.handler')->post($data);

        if (!$transaction instanceof Transaction) {
            return $this->sendError('Transaction save error!');
        }

        return $this->send($transaction->toArray());
    }
}
