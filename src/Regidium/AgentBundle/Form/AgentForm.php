<?php

namespace Regidium\AgentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

use Regidium\CommonBundle\Document\Agent;

class AgentForm extends AbstractType
{
    protected $email_exclusion;

    public function __construct($options = array())
    {
        if (array_key_exists('email_exclusion', $options)) {
            $this->email_exclusion = $options['email_exclusion'];
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('job_title', 'text', [
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
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Regidium\CommonBundle\Document\Agent'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}