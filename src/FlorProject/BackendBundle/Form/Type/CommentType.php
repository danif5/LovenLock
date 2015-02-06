<?php

namespace FlorProject\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body',
                'textarea',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        //'placeholder' => 'Comentario'
                    )
                ))
            ->add('name',
                'text',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        //'placeholder' => 'Nombre'
                    )
                ))
            ->add('email',
                'email',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-control',
                        //'placeholder' => 'Email'
                    )
                ))
            ->add('blog',
                'hidden',
                array(
                    'required' => true,
                    'data_class' => 'FlorProject\BackendBundle\Entity\Blog',
                    //'class' => 'FlorProjectBackendBundle:Blog',
                ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FlorProject\BackendBundle\Entity\Comment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'florproject_backendbundle_comment';
    }
}
