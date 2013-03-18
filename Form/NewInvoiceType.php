<?php

namespace Terramar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewInvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('amountDue')
            ->add('dateDue', 'date', array(
                'widget' => 'single_text',
                'label' => 'Start Date',
                'format' => 'MM/dd/yy'
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Terramar\Bundle\SalesBundle\Entity\Invoice'
        ));
    }

    public function getName()
    {
        return 'new_invoice';
    }
}
