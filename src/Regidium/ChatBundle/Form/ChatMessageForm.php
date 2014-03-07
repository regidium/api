<?php

namespace Regidium\ChatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

use Regidium\CommonBundle\Validator\Constraints\ExistDocument\ExistDocument;
use Regidium\CommonBundle\Document\ChatMessage;

class ChatMessageForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', 'text', [
                'required' => false,
                'constraints' => array(
                    new Constraints\NotBlank(array('message' => 'Text is empty!'))
                )
            ])
            ->add('sender_status', 'choice', [
                'required' => false,
                'choices' => ChatMessage::getStatuses(),
                'empty_data' => ChatMessage::STATUS_DEFAULT
            ])
            ->add('receiver_status', 'choice', [
                'required' => false,
                'choices' => ChatMessage::getStatuses(),
                'empty_data' => ChatMessage::STATUS_DEFAULT
            ])
            ->add('sender_uid', 'hidden', [
                'mapped' => false,
                'constraints' => array(
                    new Constraints\NotBlank(array('message' => 'Sender not found!')),
                    new ExistDocument(['repository' => 'regidium.person.repository', 'property' => 'uid'])
                )
            ])
            ->add('chat_uid', 'hidden', [
                'mapped' => false,
                'constraints' => array(
                    new Constraints\NotBlank(array('message' => 'Chat not found!')),
                    new ExistDocument(['repository' => 'regidium.chat.repository', 'property' => 'uid'])
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
            'data_class' => 'Regidium\CommonBundle\Document\ChatMessage'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chat_message_form';
    }
}