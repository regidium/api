<?php

namespace Regidium\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations;

use Regidium\CommonBundle\Controller\AbstractController;
use Regidium\CommonBundle\Document\Client;
use Regidium\CommonBundle\Document\Payment;
use Regidium\CommonBundle\Document\PaymentMethod;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Client payment controller
 *
 * @todo Update response for HTML format
 *
 * @package Regidium\ClientBundle\Controller
 * @author Alexey Volkov <alexey.wild88@gmail.com>
 *
 * @Annotations\RouteResource("Pay")
 */
class ClientPayController extends AbstractController
{
    /**
     * @todo Not realized
     * List all client payments.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param Request $uid Client uid
     *
     * @return View
     */
    public function cgetAction($uid)
    {
        return true;
    }

    /**
     * Create client payment.
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string                                    $uid Client uid
     * @param string                                    $payment_method
     *
     * @return View
     */
    public function postAction(Request $request, $uid, $payment_method)
    {
        $client = $this->get('regidium.client.handler')->one(['uid' => $uid]);
        if (!$client instanceof Client) {
            return $this->view(['errors' => ['Client not found!']]);
        }

        $payment_method = $this->get('regidium.billing.payment_method.handler')->one(['uid' => $payment_method]);
        if (!$payment_method instanceof PaymentMethod) {
            return $this->view(['errors' => ['Payment method not found!']]);
        }

        $amount = $request->request->get('amount', 0);
        if ($amount <= 0) {
            return $this->view(['errors' => ['Amount error!']]);
        }

        $payment = $this->get('regidium.billing.payment.handler')->post(
            $client,
            $payment_method,
            $amount
        );

        if (!$payment instanceof Payment) {
            return $this->view(['errors' => ['Payment save error!']]);
        }

        /** @todo Запись дейсвий */
        $client->setBalance($client->getBalance() + $amount);
        $this->get('regidium.client.handler')->edit($client);

        return $this->view($payment);
    }
}
