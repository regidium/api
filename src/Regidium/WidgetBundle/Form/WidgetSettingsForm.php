<?php

namespace Regidium\WidgetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;

class WidgetSettingsForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('header_color', 'text', [
                'required' => false
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}