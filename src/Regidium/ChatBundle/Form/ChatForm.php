<?php

namespace Regidium\ChatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

use Regidium\CommonBundle\Validator\Constraints\ExistDocument\ExistDocument;
use Regidium\CommonBundle\Document\Chat;

class ChatForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ended', 'datetime', [
                'required' => false
            ])
            ->add('user_status', 'choice', [
                    'required' => false,
                    'choices' => Chat::getStatuses()
                ])
            ->add('operator_status', 'choice', [
                    'required' => false,
                    'choices' => Chat::getStatuses()
                ])
            ->add('user_uid', 'hidden', [
                'mapped' => false,
                'constraints' => array(
                    new Constraints\NotBlank(array('message' => 'User not found!')),
                    new ExistDocument(['repository' => 'regidium.user.repository', 'property' => 'uid'])
                )
            ])
            ->add('operator_uid', 'hidden', [
                'mapped' => false,
                'required' => false,
                'constraints' => array(
                    new Constraints\NotBlank(array('message' => 'Agent not found!')),
                    new ExistDocument(['repository' => 'regidium.agent.repository', 'property' => 'uid'])
                )
            ])
            ->add('widget_uid', 'hidden', [
                'mapped' => false,
                'constraints' => array(
                    new Constraints\NotBlank(array('message' => 'Widget not found!')),
                    new ExistDocument(['repository' => 'regidium.widget.repository', 'property' => 'uid'])
                )
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Regidium\CommonBundle\Document\Chat'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chat_form';
    }
}