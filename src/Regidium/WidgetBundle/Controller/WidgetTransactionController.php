<?php

namespace Regidium\WidgetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Transaction;
use Regidium\CommonBundle\Document\Agent;

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
     * @param string  $uid       Widget UID
     * @param string  $agent_uid Agent UID
     *
     * @return View
     */
    public function postAction(Request $request, $uid, $agent_uid)
    {
        // Яндекс.Деньги
//        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
//        if (!$widget instanceof Widget) {
//            return $this->sendError('Widget not found!');
//        }
//
//        $data = $request->request->get('payment', []);
//        $data['widget_uid'] = $widget->getUid();
//
//        $payment_params = $this->container->getParameter('payment');
//        $data['receiver'] = $payment_params['yd_client_id'];
//
//        $transaction = $this->get('regidium.billing.transaction.handler')->post($data);
//
//        if (!$transaction instanceof Transaction) {
//            return $this->sendError('Transaction save error!');
//        }
//
//        return $this->send($transaction->toArray());
        // ROBOKASSA
        $widget = $this->get('regidium.widget.handler')->one(['uid' => $uid]);
        if (!$widget instanceof Widget) {
            return $this->sendError('Widget not found!');
        }

        $agent = $this->get('regidium.agent.handler')->one(['uid' => $agent_uid]);
        if (!$agent instanceof Agent) {
            return $this->sendError('Agent not found!');
        }

        $data = $request->request->get('payment', []);
        $data['widget_uid'] = $widget->getUid();
        $data['agent_uid'] = $agent->getUid();

        $payment_params = $this->container->getParameter('payment');
        $data['receiver'] = $payment_params['rc_login'];

        $transaction = $this->get('regidium.billing.transaction.handler')->post($data);

        if (!$transaction instanceof Transaction) {
            return $this->sendError('Transaction save error!');
        }

        $crc = md5($payment_params['rc_login'].':'.$transaction->getSum().':'.$transaction->getNumber().':'.$payment_params['rc_pass1']);

        $url = $payment_params['rc_url'].'?MrchLogin='.$payment_params['rc_login'].'&OutSum='.$transaction->getSum().'&InvId='.$transaction->getNumber().'&Desc='.$transaction->getNumber().'&SignatureValue='.$crc;

        return $this->send(['transaction' => $transaction->toArray(), 'url' => $url]);
    }
}
