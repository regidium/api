<?php

namespace Regidium\BillingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Transaction;

/**
 * Widget payment controller
 *
 * @package Regidium\WidgetBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Payment")
 */
class PaymentController extends AbstractController
{
    /**
     * Поступила оплаты виджета.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Возвращает при успешном выполнении"
     *   }
     * )
     *
     * @param Request $request
     *
     * @return View
     */
    public function postAction(Request $request)
    {
        // Яндекс.Деньги
//        $transaction_uid = $request->request->get('label', '');
//
//        $sha_string_calc =
//            $request->request->get('notification_type', '') .
//            $request->request->get('operation_id', '') .
//            $request->request->get('amount', '') .
//            $request->request->get('currency', '') .
//            $request->request->get('datetime', '') .
//            $request->request->get('sender', '') .
//            $request->request->get('codepro', '') .
//            $this->container->getParameter('payment.yd_notification_secret') .
//            $transaction_uid
//        ;
//
//        $sha_string = $request->request->get('sha1_hash');
//
//        if ($sha_string != hash('sha1', $sha_string_calc)) {
//            return $this->sendError('Notification is failed!', 402);
//        }
//
//        $transaction = $this->get('regidium.billing.transaction.handler')->one(['uid' => $transaction_uid]);
//        if (!$transaction instanceof Transaction) {
//            return $this->sendError('Transaction not found!');
//        }
//
//        $transaction = $this->get('regidium.billing.transaction.handler')->pay($transaction, $request->request->all());
//        if (!$transaction instanceof Transaction) {
//            return $this->sendError('Transaction not found!');
//        }
//
//
//        /** @todo вынести в HTTP Notification от ЯД */
////        $widget->setBalance($widget->getBalance() + $sum);
////        $this->get('regidium.widget.handler')->edit($widget);
//
//        return $this->send($transaction->toArray());
        // ROBOKASSA
    }
}
