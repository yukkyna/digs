<?php

namespace Digs\InformationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class InformationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('message', 'textarea', array(
				'required' => false,
				'constraints' => array(
					new NotBlank()
				)
			))
//            ->add('createdAt')
//            ->add('updatedAt')
//            ->add('status')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Digs\InformationBundle\Entity\Information'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'digs_informationbundle_information';
    }
}
