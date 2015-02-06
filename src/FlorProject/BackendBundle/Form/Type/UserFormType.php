<?php
/**
 * Created by PhpStorm.
 * User: Gandalf_X
 * Date: 13/11/2014
 * Time: 11:57 PM
 */

namespace FlorProject\BackendBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class UserFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        //adding custom fields
        $builder
            ->add(
                'firstName',
                'text',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'id' => 'user-first-name',
                        'class' => 'form-control',
                        'placeholder' => 'Nombre'
                    )
                )
            )
            ->add(
                'lastName',
                'text',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'id' => 'user-last-name',
                        'class' => 'form-control',
                        'placeholder' => 'Apellido'
                    )
                )
            )
            ->add(
                'country',
                'text',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'id' => 'country-text',
                        'class' => 'form-control',
                        'placeholder' => 'País'
                    )
                )
            )
            ->add(
            'birthDate',
            'text',
            array(
                'required' => true,
                'label' => false,
                'attr' => array(
                    'id' => 'user-birth-date',
                    'class' => 'form-control',
                    'placeholder' => 'Fecha Nacimiento'
                    )
                )
            )
            ->add(
            'gender',
            'text',
            array(
                'required' => false,
                'label' => false,
                'attr' => array(
                    'id' => 'user-gender',
                    'class' => 'form-control',
                    'placeholder' => 'Género'
                    )
                )
            );

    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'FlorProject\BackendBundle\Entity\User'
        );
    }

    public function getName()
    {
        return 'flor_user_form';
    }
}

