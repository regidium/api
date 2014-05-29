<?php

namespace Regidium\BillingBundle\Handler;

use Regidium\CommonBundle\Handler\AbstractHandler;

use Regidium\BillingBundle\Form\TransactionForm;
use Regidium\CommonBundle\Document\Widget;
use Regidium\CommonBundle\Document\Transaction;

class TransactionHandler extends AbstractHandler
{
    /**
     * Get one transaction by criteria.
     *
     * @param array $criteria
     *
     * @return Transaction
     */
    public function one(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * Get transactions by criteria.
     *
     * @param array $criteria
     *
     * @return Transaction
     */
    public function get(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * Get all transactions.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy([], null, $limit, $offset);
    }

    /**
     * Создание новой транзакции
     *
     * @param array $data
     *
     * @return string|array|Transaction
     */
    public function post(array $data)
    {
        return $this->processForm($this->createEntity(), $data, 'POST');
    }

    /**
     * Изменение транзакции.
     *
     * @param Transaction $transaction
     * @param array $data
     *
     * @return string|array|Transaction
     */
    public function put(Transaction $transaction, array $data)
    {
        return $this->processForm($transaction, $data, 'PUT');
    }

    /**
     * Транзакции оплачена.
     *
     * @param Transaction $transaction
     * @param array $data
     *
     * @return string|array|Transaction
     */
    public function pay(Transaction $transaction, array $data)
    {
        try {
            $payment_at = new \DateTime($data['datetime']);
            $transaction->setPaymentAt($payment_at->getTimestamp());
            $transaction->setStatus(Transaction::STATUS_PAYMENT);
            $transaction->setCodepro($data['codepro']);
            $transaction->setOperationId($data['operation_id']);
            $transaction->setSender($data['sender']);

            $this->dm->persist($transaction);
            $this->dm->flush($transaction);

            return $transaction;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Create a new Transaction.
     *
     * @param Transaction $transaction
     * @param array       $data
     * @param string      $method
     *
     * @return string|array|Transaction
     */
    public function processForm(Transaction $transaction, array $data, $method = 'PUT')
    {
        $form = $this->form_factory->create(new TransactionForm(), $transaction, ['method' => $method]);
        $form->submit($data, 'PATCH' !== $method);
        if ($form->isValid()) {
            $transaction = $form->getData();
            if (!$transaction instanceof Transaction) {
                return 'Server error';
            }

            $widget = $this->dm->getRepository('Regidium\CommonBundle\Document\Widget')->findOneBy(['uid' => $form->get('widget_uid')->getData()]);
            $transaction->setWidget($widget);

            $this->dm->persist($transaction);
            $this->dm->flush($transaction);

            return $transaction;
        }

        return $this->getFormErrors($form);
    }
}