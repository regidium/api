<?php

namespace Regidium\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;
use Regidium\CommonBundle\Validator\Constraints\UniqueDocument\UniqueDocument;

/**
 * @todo В проверке уникальности email исключать текущий email
*/
class UserForm extends AbstractType
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
            ->add('fullname', 'text', [
                    'required' => false
                ])
            ->add('email', 'email', [
                    'required' => false,
                    'constraints' => array(
//                        new Constraints\Email(array('message' => 'Wrong Email')),
//                        new Constraints\NotBlank(array('message' => 'Blank Email')),
                        new UniqueDocument(array('repository' => 'regidium.user.repository', 'property' => 'email', 'exclusion' => $this->email_exclusion))
                    )
                ])
            ->add('password', 'password', [
                'required' => false
            ])
            ->add('status', 'integer', [
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
            'data_class' => 'Regidium\UserBundle\Document\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }
}