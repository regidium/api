<?php

namespace Regidium\AuthBundle\Form\Registration;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;
use Regidium\CommonBundle\Validator\Constraints\UniqueDocument\UniqueDocument;

class RegistrationForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname', 'text', [
                    'required' => false
                ])
            ->add('email', 'email', [
                    'constraints' => array(
                        new Constraints\Email(array('message' => 'Wrong Email')),
                        new Constraints\NotBlank(array('message' => 'Blank Email')),
                        new UniqueDocument(array('repository' => 'regidium.user.repository', 'property' => 'email'))
                    )
                ])
            ->add('password', 'password')
            ->add('remember', 'radio', [
                'required' => false
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Regidium\AuthBundle\Document\Auth'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'registration';
    }
}