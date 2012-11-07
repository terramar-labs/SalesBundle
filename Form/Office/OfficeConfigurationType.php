<?php

namespace TerraMar\Bundle\SalesBundle\Form\Office;

use Symfony\Component\Form\AbstractType;
use Orkestra\Transactor\Entity\Transaction\NetworkType;
use Orkestra\Bundle\ApplicationBundle\Helper\FormHelper;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OfficeConfigurationType extends AbstractType
{
    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Helper\FormHelper
     */
    protected $helper;

    public function __construct(FormHelper $helper)
    {
        $this->helper = $helper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $factory = $builder->getFormFactory();
        $helper = $this->helper;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($factory, $helper) {
            $data = $event->getData();

            if (null === $data) {
                return;
            }

            $form = $event->getForm();

            $cardCredentials = $data->getCardCredentials();
            $achCredentials = $data->getAchCredentials();

            $form->add($factory->createNamed(
                'cardCredentials',
                $helper->getType($cardCredentials, 'Orkestra\Transactor\Entity\Credentials'),
                $cardCredentials,
                array('required' => false, 'label' => 'Card Credentials', 'network' => new NetworkType(NetworkType::CARD))
            ));

            $form->add($factory->createNamed(
                'achCredentials',
                $helper->getType($achCredentials, 'Orkestra\Transactor\Entity\Credentials'),
                $achCredentials,
                array('required' => false, 'label' => 'ACH Credentials', 'network' => new NetworkType(NetworkType::ACH))
            ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TerraMar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration'
        ));
    }

    public function getName()
    {
        return 'officeconfiguration';
    }
}
