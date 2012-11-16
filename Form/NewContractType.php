<?php

namespace TerraMar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TerraMar\Bundle\SalesBundle\Repository\AgreementRepository;
use TerraMar\Bundle\SalesBundle\Repository\SalespersonRepository;

class NewContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $office = $options['office'];

        $builder->add('billingFrequency', 'enum', array(
                'enum' => 'TerraMar\Bundle\SalesBundle\Entity\Contract\BillingFrequency',
                'label' => 'Billing Frequency'
            ))
            ->add('dateStart', 'date', array(
                'widget' => 'single_text',
                'label' => 'Start Date',
                'format' => 'MM/dd/yy'
            ))
            ->add('salesperson', null, array(
                'query_builder' => function(SalespersonRepository $er) use ($office) {
                    return $er->getFindByOfficeQueryBuilder($office);
                }
            ))
            ->add('agreement', null, array(
                'query_builder' => function(AgreementRepository $er) use ($office) {
                    return $er->getFindByOfficeQueryBuilder($office);
                }
            ))
            ->add('signature', 'signature');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'office'
        ));

        $resolver->setDefaults(array(
            'data_class' => 'TerraMar\Bundle\SalesBundle\Entity\Contract'
        ));
    }

    public function getName()
    {
        return 'new_contract';
    }
}
