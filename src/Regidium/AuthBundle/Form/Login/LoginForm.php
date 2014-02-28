<?php

namespace Regidium\AuthBundle\Form\Login;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
                    'constraints' => [
                        new Constraints\Email(['message' => 'Wrong Email']),
                        new Constraints\NotBlank(['message' => 'Blank Email']),
                        new ExistDocument(['repository' => 'regidium.user.repository', 'property' => 'email'])
                    ]
                ])
            ->add('password', 'password')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'login_form';
    }
}