<?php

namespace Regidium\AgentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

use Regidium\CommonBundle\Validator\Constraints\ExistDocument\ExistDocument;
use Regidium\CommonBundle\Validator\Constraints\UniqueDocument\UniqueDocument;
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
            ->add('first_name', 'text', [
                    'required' => false,
                    'description' => 'Agent First name'
                ])
            ->add('last_name', 'text', [
                    'required' => false,
                    'description' => 'Agent Last name'
                ])
            ->add('avatar', 'text', [
                    'required' => false,
                    'description' => 'Agent avatar file'
                ])
            ->add('job_title', 'text', [
                    'required' => false,
                    'description' => 'Agent job title'
                ])
            ->add('accept_chats', 'radio', [
                    'required' => false
                ])
            ->add('type', 'integer', [
                    'required' => false,
                    'description' => 'Agent type',
                    'data' => Agent::TYPE_OPERATOR
                ])
            ->add('status', 'integer', [
                    'required' => false,
                    'description' => 'Agent status',
                    'data' => Agent::STATUS_DEFAULT
                ])
            ->add('render_visitors_period', 'integer', [
                    'required' => false,
                    'description' => 'Agent render visitors type',
                    'data' => Agent::RENDER_VISITORS_SESSION
                ])
            ->add('accept_chats', 'choice', [
                    'required' => false,
                    'choices'   => [true, false],
                    'data' => true
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

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                $email_exclusion = null;
                if($data instanceof Agent) {
                    $email_exclusion = $data->getEmail();
                }

                $form->add('email', 'email', [
                        'required' => true,
                        'constraints' => [
                            new UniqueDocument([
                                'repository' => 'regidium.agent.repository',
                                'property' => 'email',
                                'exclusion' => $email_exclusion
                            ])
                        ]
                    ])
                ;
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if (isset($data['password']) && $data['password']) {
                    $form->add('password', 'password', [
                            'required' => false
                        ])
                    ;
                }
            }
        );
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