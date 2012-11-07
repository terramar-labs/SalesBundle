<?php

namespace TerraMar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'tel')
            ->add('altPhone', 'tel', array('label' => 'Alt. Phone', 'required' => false))
            ->add('street')
            ->add('suite', 'text', array('label' => 'Suite/Apt', 'required' => false))
            ->add('city')
            ->add('postalCode', 'text', array('label' => 'Zip'))
            ->add('region', 'entity', array(
                'label' => 'State',
                'class' => 'Orkestra\Bundle\ApplicationBundle\Entity\Contact\Region',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('r')
                        ->join('r.country', 'c', 'WITH', 'c.code = :code');
                    $qb->setParameter(':code', 'US');

                    return $qb;
                }
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Orkestra\Bundle\ApplicationBundle\Entity\Contact\Address'
        ));
    }

    public function getName()
    {
        return 'address';
    }
}
