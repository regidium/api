<?php

namespace Regidium\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;
use Regidium\CommonBundle\Validator\Constraints\UniqueDocument\UniqueDocument;
use Regidium\CommonBundle\Document\User;

/**
 * @todo В проверке уникальности email исключать текущий email
*/
class UserForm extends AbstractType
{

    protected $email_exclusion = null;

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
            ->add('status', 'choice', [
                'choices' => User::getStatuses()
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Regidium\CommonBundle\Document\User'
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