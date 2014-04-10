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
            ->add('started_at', 'integer', [
                'required' => false
            ])
            ->add('ended_at', 'integer', [
                'required' => false
            ])
            ->add('status', 'choice', [
                'required' => false,
                'choices' => Chat::getStatuses(),
                'empty_data' => Chat::STATUS_ONLINE
            ])
            ->add('socket_id', 'text', [
                'required' => false
            ])
            ->add('old_status', 'choice', [
                'required' => false,
                'choices' => Chat::getStatuses(),
                'empty_data' => Chat::STATUS_ONLINE
            ])
            ->add('opened', 'radio', [
                'required' => false,
                'empty_data' => false
            ])
            ->add('user', 'hidden')
            ->add('widget_uid', 'hidden', [
                'mapped' => false,
                'constraints' => [
                    new Constraints\NotBlank(['message' => 'Widget not found!']),
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
            'data_class' => 'Regidium\CommonBundle\Document\Chat'
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