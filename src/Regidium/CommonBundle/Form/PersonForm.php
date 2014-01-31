<?php

namespace Regidium\CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;
use Regidium\CommonBundle\Validator\Constraints\UniqueDocument\UniqueDocument;

use Regidium\CommonBundle\Document\Person;

class PersonForm extends AbstractType
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
            ->add('avatar', 'text', [
                    'required' => false
                ])
            ->add('email', 'email', [
                    'required' => false,
                    'constraints' => array(
                        new UniqueDocument(array('repository' => 'regidium.person.repository', 'property' => 'email', 'exclusion' => $this->email_exclusion))
                    )
                ])
            ->add('password', 'password', [
                    'required' => false
                ])
            ->add('status', 'choice', [
                    'required' => false,
                    'choices' => Person::getStatuses()
                ])
            ->add('country', 'text', [
                    'required' => false
                ])
            ->add('city', 'text', [
                    'required' => false
                ])
            ->add('ip', 'text', [
                    'required' => false
                ])
            ->add('os', 'text', [
                    'required' => false
                ])
            ->add('browser', 'text', [
                    'required' => false
                ])
            ->add('keyword', 'text', [
                    'required' => false
                ])
            ->add('language', 'text', [
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
            'data_class' => 'Regidium\CommonBundle\Document\Person'
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