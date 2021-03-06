<?php

namespace Regidium\BillingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

use Regidium\CommonBundle\Validator\Constraints\ExistDocument\ExistDocument;
use Regidium\CommonBundle\Document\Transaction;

class TransactionForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('number', 'integer', [
//                'description' => 'Transaction number'
//            ])
            ->add('payment_method', 'integer', [
                    'description' => 'Transaction Payment Method'
                ])
            ->add('sum', 'number', [
                'description' => 'Transaction Sum'
            ])
            ->add('receiver', 'text', [
                    'description' => 'Transaction Receiver'
                ])
            ->add('created_at', 'integer', [
                'required' => false,
                'description' => 'Transaction Create date'
            ])
            ->add('payment_at', 'integer', [
                'required' => false,
                'description' => 'Transaction Payment date'
            ])
            ->add('status', 'integer', [
                'description' => 'Transaction Status',
                'data' => Transaction::STATUS_NOT_PAYMENT
            ])
            ->add('agent_uid', 'hidden', [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new ExistDocument([
                        'repository' => 'regidium.agent.repository',
                        'property' => 'uid'
                    ])
                ]
            ])
            ->add('widget_uid', 'hidden', [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new ExistDocument([
                        'repository' => 'regidium.widget.repository',
                        'property' => 'uid'
                    ])
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
                'data_class' => 'Regidium\CommonBundle\Document\Transaction'
            ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}