<?php

namespace TerraMar\Bundle\SalesBundle\Form\OfficeUser;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $office = $options['office'];
        $showAssigned = $options['showAssignedTo'];

        $builder->add('name', null, array('label' => 'Title'))
            ->add('description')
            ->add('alertPriority', 'enum', array(
            'label' => 'Alert Priority',
            'enum' => 'TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority'
            ));

        if ($showAssigned) {
            $builder->add('assignedTo', 'entity', array(
                'label' => 'Assign Alert To',
                'empty_value' => 'Select ...',
                'class' => 'Orkestra\Bundle\ApplicationBundle\Entity\User',
                'query_builder' => function (EntityRepository $er) use ($office) {
                    return $er->createQueryBuilder('u')
                        ->from('TerraMar\Bundle\SalesBundle\Entity\OfficeUser', 'ou')
                        ->where('ou.user = u')
                        ->andWhere('ou.active = true')
                        ->andWhere('u.active = true')
                        ->andWhere('ou.office = :office')
                        ->setParameter('office', $office);
                }
            ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'showAssignedTo', 'office'
        ));

    }

    public function getName()
    {
        return 'office_user_alert';
    }
}
