<?php

namespace Digs\EntryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntryCommentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message')
//            ->add('createdAt')
//            ->add('status')
//            ->add('member')
//            ->add('entry')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Digs\EntryBundle\Entity\EntryComment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'digs_entrybundle_entrycomment';
    }
}
