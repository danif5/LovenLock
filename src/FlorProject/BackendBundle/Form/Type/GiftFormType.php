<?php
/**
 * Created by PhpStorm.
 * User: Gandalf_X
 * Date: 13/11/2014
 * Time: 11:57 PM
 */

namespace FlorProject\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class GiftFormType extends AbstractType {

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
                        'id' => 'gift-name',
                        'class' => 'form-control',
                        'placeholder' => 'Nombre'
                    )
                )
            )
            ->add(
                'price',
                'money',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'id' => 'gift-price',
                        'class' => 'form-control',
                        'placeholder' => 'Precio'
                    ),
                    'precision' => 2
                )
            )
            ->add(
                'family',
                'entity',
                array(
                    'required' => true,
                    'label' => false,
                    'class' => 'FlorProjectBackendBundle:Family',
                    'property' => 'familyName',
                    'empty_value' => 'Seleccione una familia',
                    'attr' => array(
                        'id' => 'family-text',
                        'class' => 'form-control',
                        'placeholder' => 'Familia'
                    )
                )
            )
            ->add(
                'file',
                'file',
                array(
                    'required' => false,
                    'label' => false,
                    'attr' => array('id' => 'photo-upload')
                )
            );
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'FlorProject\BackendBundle\Entity\Gift'
        );
    }

    public function getName()
    {
        return 'flor_gift_form';
    }
}

