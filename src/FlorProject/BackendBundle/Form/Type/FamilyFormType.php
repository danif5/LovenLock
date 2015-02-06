<?php
/**
 * Created by PhpStorm.
 * User: Gandalf_X
 * Date: 13/11/2014
 * Time: 11:57 PM
 */

namespace FlorProject\BackendBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;

class FamilyFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       //adding custom fields
        $builder
            ->add(
                'familyName',
                'text',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'id' => 'name',
                        'class' => 'form-control',
                        'placeholder' => 'Nombre'
                    )
                )
            )
            ->add(
                'giftType',
                'choice',
                array(
                    'choices'   => array(
                        '1'   => 'Candado',
                        '2' => 'Flor',
                        '3' => 'Caja'),
                    'required' => true,
                    'label' => false,
                    'empty_value' => 'Seleccione un tipo',
                    'attr' => array(
                        'id' => 'type-integer',
                        'class' => 'form-control'
                    )

                )
            )
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'FlorProject\BackendBundle\Entity\Family'
        );
    }

    public function getName()
    {
        return 'flor_family_form';
    }
}

