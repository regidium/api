<?php

namespace Regidium\AuthBundle\Form\Login;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

use Regidium\CommonBundle\Validator\Constraints\ExistDocument\ExistDocument;

class LoginForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', [
                    'constraints' => array(
                        new Constraints\Email(array('message' => 'Wrong Email')),
                        new Constraints\NotBlank(array('message' => 'Blank Email')),
                        new ExistDocument(array('repository' => 'regidium.user.repository', 'property' => 'email'))
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
            'data_class' => 'Regidium\CommonBundle\Document\Auth'
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