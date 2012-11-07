<?php

namespace TerraMar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use TerraMar\Bundle\SalesBundle\Entity\Contract\BillingFrequency;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AgreementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $frequencies = array(
            BillingFrequency::MONTHLY,
            BillingFrequency::BI_MONTHLY,
            BillingFrequency::QUARTERLY,
            BillingFrequency::SEMI_ANNUALLY,
            BillingFrequency::ANNUALLY
        );

        $builder->add('name')
            ->add('description')
            ->add('active', null, array('required' => false, 'data' => true))
            ->add('length', 'choice', array(
                'label' => 'Length (in months)',
                'choices' => array_combine(range(1, 13), range(1, 13))
            ))
            ->add('billingFrequencies', 'choice', array(
                'multiple' => true,
                'label' => 'Service Frequencies',
                'expanded' => true,
                'choices' => array_combine($frequencies, $frequencies)
            ))
            ->add('agreementBody', null, array('label' => 'Agreement Body', 'required' => false))
            ->add('welcomeLetter', null, array('label' => 'Welcome Letter', 'required' => false))
            ->add('invoiceIntro', null, array('label' => 'Invoice Introduction', 'required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TerraMar\Bundle\SalesBundle\Entity\Agreement'
        ));
    }

    public function getName()
    {
        return 'agreement';
    }
}
