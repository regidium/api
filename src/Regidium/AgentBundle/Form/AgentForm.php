<?php

namespace Regidium\AgentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

use Regidium\CommonBundle\Validator\Constraints\ExistDocument\ExistDocument;
use Regidium\CommonBundle\Document\Agent;

class AgentForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('job_title', 'text', [
                'required' => false,
                'description' => 'Agent Job title'
            ])
            ->add('type', 'integer', [
                'required' => false
            ])
            ->add('status', 'integer', [
                'required' => false
            ])
            ->add('accept_chats', 'radio', [
                'required' => false
            ])
            ->add('type', 'choice', [
                'required' => false,
                'choices' => Agent::getTypes()
            ])
            ->add('status', 'choice', [
                'required' => false,
                'choices' => Agent::getStatuses()
            ])
            ->add('accept_chats', 'choice', [
                'required' => false,
                'choices'   => [true, false]
            ])
            ->add('widget_uid', 'hidden', [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new ExistDocument(['repository' => 'regidium.widget.repository', 'property' => 'uid'])
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
            'data_class' => 'Regidium\CommonBundle\Document\Agent'
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