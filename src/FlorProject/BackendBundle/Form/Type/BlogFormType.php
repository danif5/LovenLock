<?php

namespace FlorProject\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BlogFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                'text',
                array(
                    'required' => true,
                    'label' => false,
                    'attr' => array(
                        'id' => 'blog-title',
                        'class' => 'form-control',
                        'placeholder' => 'Título'
                    )
                ))
            ->add(
                'body',
                null,
                array(
                    'required' => false,
                    'label' => false,
                    'attr' => array(
                        'id' => 'blog-body',
                        'class' => 'form-control',
                        'placeholder' => 'Contenido'
                    )
                ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FlorProject\BackendBundle\Entity\Blog'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'florproject_backendbundle_blog';
    }
}
