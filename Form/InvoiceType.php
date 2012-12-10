<?php

namespace TerraMar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use TerraMar\Bundle\SalesBundle\Model\SalesProfileInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvoiceType extends AbstractType
{
    protected $profile;

    public function __construct(SalesProfileInterface $profile)
    {
        $this->profile = $profile;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('payments', 'collection', array(
                'type' => new Invoice\PaymentType($this->profile),
                'allow_add' => true,
                'by_reference' => false,
                'property_path' => false,
                'error_bubbling' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TerraMar\Bundle\SalesBundle\Entity\Invoice'
        ));
    }

    public function getName()
    {
        return 'invoice';
    }
}
