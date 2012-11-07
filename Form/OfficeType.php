<?php

namespace TerraMar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OfficeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');

        if (!empty($options['include_parent']) || true === $options['include_parent']) {
            $builder->add('parent', null, array('required' => false, 'empty_value' => ''));
        }

        $builder->add('url', 'url', array('required' => false))
            ->add('fax', 'tel', array('required' => false))
            ->add('contactName', null, array('label' => 'Contact Name'))
            ->add('contactAddress', new AddressType())
            ->add('logo', 'file', array('property_path' => false, 'required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'include_parent' => false,
            'data_class' => 'TerraMar\Bundle\SalesBundle\Entity\Office'
        ));
    }

    public function getName()
    {
        return 'office';
    }
}
