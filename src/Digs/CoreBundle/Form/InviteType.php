<?php

namespace Digs\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InviteType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('ticket')
            ->add('email')
            ->add('group', 'entity', array(
				'class' => 'DigsCoreBundle:MemberGroup',
				'property' => 'name',
				'multiple' => true,
				'expanded' => true,
				'mapped' => false,
			))
//            ->add('category_ids')
//            ->add('createdAt')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Digs\CoreBundle\Entity\Invite'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'digs_corebundle_invite';
    }
}
