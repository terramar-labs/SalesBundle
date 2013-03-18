<?php

namespace Terramar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('status', 'enum', array(
            'enum' => 'Terramar\Bundle\SalesBundle\Entity\Contract\ContractStatus'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Terramar\Bundle\SalesBundle\Entity\Contract'
        ));
    }

    public function getName()
    {
        return 'contract';
    }
}
