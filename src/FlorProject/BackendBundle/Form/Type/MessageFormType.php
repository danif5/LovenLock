<?php

namespace FlorProject\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilder;


class MessageFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Nombre y Apellidos'
                    )
                ))
            ->add(
                'email',
                'email',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Su correo electrónico'
                    )
                ))
            ->add(
                'phone',
                'text',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Su correo electrónico'
                    )
                ))
            ->add(
                'subject',
                'text',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Asunto'
                    )
                ))
            ->add(
                'message',
                'textarea',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'Mensaje'
                    )
                ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            //'virtual' => true,
            'csrf_protection' => false,
            'csrf_field_name' => false
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'florproject_backendbundle_message';
    }
}
