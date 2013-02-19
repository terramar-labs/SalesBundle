<?php

namespace Terramar\Bundle\SalesBundle\Form\OfficeUser;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $office = $options['office'];

        $builder->add('name', 'text', array('label' => 'Title'))
            ->add('description')
            ->add('alertPriority', 'enum', array(
                'label' => 'Alert Priority',
                'enum' => 'Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority'
            ))
            ->add('dueDate', 'date', array(
                'label' => 'Due Date',
                'required' => false,
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy'
            ))
            ->add('alertType', 'enum', array(
                'label' => 'Alert Type',
                'empty_value' => 'Select ...',
                'enum' => 'Terramar\Bundle\SalesBundle\Entity\Alert\AlertType'
            ))
            ->add('assignedTo', 'entity', array(
                'label' => 'Assign Alert To',
                'empty_value' => 'Select ...',
                'class' => 'Terramar\Bundle\SalesBundle\Entity\OfficeUser',
                'query_builder' => function (EntityRepository $er) use ($office) {
                    return $er->createQueryBuilder('ou')
                        ->join('ou.user', 'u')
                        ->andWhere('ou.office = :office')
                        ->andWhere('ou.active = true')
                        ->andWhere('u.active = true')
                        ->setParameter('office', $office);
                }
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'office'
        ));

    }

    public function getName()
    {
        return 'office_user_tack';
    }
}
