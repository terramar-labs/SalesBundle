<?php

namespace TerraMar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('length')
            ->add('status')
            ->add('dateStart')
            ->add('dateEnd')
            ->add('active')
            ->add('dateModified')
            ->add('dateCreated')
            ->add('profile')
            ->add('agreement')
            ->add('signature')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TerraMar\Bundle\SalesBundle\Entity\Contract'
        ));
    }

    public function getName()
    {
        return 'terramar_bundle_salesmanagementbundle_contracttype';
    }
}
