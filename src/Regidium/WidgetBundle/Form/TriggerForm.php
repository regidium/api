<?php

namespace Regidium\WidgetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

use Regidium\CommonBundle\Document\Trigger;

class TriggerForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('uid', 'hidden', [
            //     'required' => false
            // ])
            ->add('name', 'text', [
                'required' => false
            ])
            ->add('priority', 'integer', [
                'required' => false
            ])
            ->add('event', 'integer', [
                'required' => false,
                //'choices' => Trigger::getEvents()
            ])
            ->add('event_params', 'text', [
                'required' => false
            ])
            ->add('result', 'integer', [
                'required' => false,
                //'choices' => Trigger::getEvents()
            ])
            ->add('result_params', 'text', [
                'required' => false
            ])
            ->add('widget_uid', 'hidden', [
                'mapped' => false
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Regidium\CommonBundle\Document\Trigger'
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