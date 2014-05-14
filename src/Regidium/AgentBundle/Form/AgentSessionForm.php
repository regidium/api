<?php

namespace Regidium\ChatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

use Regidium\CommonBundle\Document\Agent;

class AgentSessionForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', 'text', [
                    'required' => false
                ])
            ->add('city', 'text', [
                    'required' => false
                ])
            ->add('ip', 'text', [
                    'required' => false
                ])
            ->add('device', 'text', [
                    'required' => false
                ])
            ->add('os', 'text', [
                    'required' => false
                ])
            ->add('browser', 'text', [
                    'required' => false
                ])
            ->add('language', 'text', [
                    'required' => false
                ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Regidium\CommonBundle\Document\AgentSession'
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