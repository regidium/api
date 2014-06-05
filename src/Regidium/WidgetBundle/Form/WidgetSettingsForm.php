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
            ->add('language', 'text', [
                'required' => false
            ])
            ->add('header_color', 'text', [
                'required' => false
            ])
            ->add('company_logo', 'text', [
                'required' => false
            ])
            ->add('title_online', 'text', [
                'required' => false
            ])
            ->add('title_offline', 'text', [
                'required' => false
            ])
            ->add('explanatory_message', 'text', [
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