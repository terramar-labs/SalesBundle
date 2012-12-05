<?php

namespace TerraMar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OfficeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('url', 'url', array('required' => false))
            ->add('fax', 'tel', array('required' => false))
            ->add('contactName', null, array('label' => 'Contact Name'))
            ->add('contactAddress', new AddressType())
            ->add('logo', 'file', array('property_path' => false, 'required' => false))
            ->add('parent', 'hidden', array('required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TerraMar\Bundle\SalesBundle\Entity\Office'
        ));
    }

    public function getName()
    {
        return 'office';
    }
}
