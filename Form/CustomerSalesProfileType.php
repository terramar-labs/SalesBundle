<?php

namespace Terramar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomerSalesProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active')
            ->add('dateModified')
            ->add('dateCreated')
            ->add('customer')
            ->add('office')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile'
        ));
    }

    public function getName()
    {
        return 'terramar_bundle_salesmanagementbundle_customersalesprofiletype';
    }
}
