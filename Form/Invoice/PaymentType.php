<?php

namespace TerraMar\Bundle\SalesBundle\Form\Invoice;

use Symfony\Component\Form\AbstractType;
use TerraMar\Bundle\SalesBundle\Model\SalesProfileInterface;
use Orkestra\Transactor\Entity\Transaction\NetworkType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentType extends AbstractType
{
    protected $profile;

    public function __construct(SalesprofileInterface $profile)
    {
        $this->profile = $profile;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $profile = $this->profile;

        $builder->add('method', 'enum', array(
                'enum' => 'Orkestra\Transactor\Entity\Transaction\NetworkType',
                'exclude' => NetworkType::MFA,
                'labels' => array(
                    NetworkType::POINTS => 'Account Credit'
                )
            ))
            ->add('account', 'entity_choice', array(
                'class' => 'Orkestra\Transactor\Entity\AbstractAccount',
                'query_builder' => function() use ($profile) {
                    return $profile->getAccounts();
                }
            ))
            ->add('amount');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => false,
            'data_class' => 'TerraMar\Bundle\SalesBundle\Model\Invoice\Payment'
        ));
    }

    public function getName()
    {
        return 'payment';
    }
}
