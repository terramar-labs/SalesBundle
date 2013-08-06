<?php

namespace Terramar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Terramar\Bundle\SalesBundle\Model\SalesProfileInterface;
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
                'options' => array(
                    'allow_points' => $options['allow_points'],
                    'allow_cash' => $options['allow_cash'],
                    'allow_check' => $options['allow_check']
                ),
                'allow_add' => true,
                'by_reference' => false,
                'mapped' => false,
                'error_bubbling' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Terramar\Bundle\SalesBundle\Entity\Invoice',
            'allow_points' => true,
            'allow_cash' => true,
            'allow_check' => true,
        ));
    }

    public function getName()
    {
        return 'invoice';
    }
}
