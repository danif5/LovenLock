<?php
/**
 * Created by PhpStorm.
 * User: Karel
 * Date: 9/25/14
 * Time: 2:33 PM
 */

namespace FlorProject\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonalInfoFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                "dateOfBirth",
                "birthday",
                array(
                    'required' => true,
                    'label' => 'Date of Birth'
                )
            )
            ->add(
                "residenceCountry",
                null,
                array(
                    'required' => true,
                    'property' => 'Name',
                    'label' => 'Residence Country',
                    'attr' => array('placeholder' => 'Residence Country')
                )
            )
            ->add(
                "profession",
                null,
                array(
                    'required' => true,
                    'property' => 'Name',
                    'label' => 'Profession',
                    'attr' => array('placeholder' => 'Profession')
                )
            )
            ->add(
                "city",
                null,
                array(
                    'property' => 'Name',
                    'required' => true,
                    'label' => 'City',
                    'attr' => array('placeholder' => 'City')
                )
            )
            ->add(
                "language",
                null,
                array(
                    'required' => true,
                    'property' => 'Name',
                    'label' => 'Language',
                    'attr' => array('placeholder' => 'Language')
                )
            )
            ->add(
                "mobileNumber",
                null,
                array(
                    'label' => 'Mobile Number',
                    'attr' => array('placeholder' => 'Mobile Number'),
                )
            );
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Sabrus\Domain\User\PersonalInfo',
            )
        );
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'form_type_personal_info';
    }

}
