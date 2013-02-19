<?php

namespace Terramar\Bundle\SalesBundle\Form\Customer;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ToDoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $office = $options['office'];

        $builder->add('name', null, array('label' => 'Title'))
            ->add('description')
            ->add('dueDate', 'date', array(
                'label' => 'Due Date',
                'widget' => 'single_text',
                'format' => 'MM/dd/yy'
            ))
            ->add('alertPriority', 'enum', array(
                'label' => 'Alert Priority',
                'enum' => 'Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority'
            ))
            ->add('assignedTo', 'entity', array(
                'required' => false,
                'label' => 'Assign Alert To',
                'empty_value' => 'Select ...',
                'class' => 'Terramar\Bundle\SalesBundle\Entity\OfficeUser',
                'query_builder' => function (EntityRepository $er) use ($office) {
                    return $er->createQueryBuilder('ou')
                        ->join('ou.user', 'u')
                        ->andWhere('ou.active = true')
                        ->andWhere('u.active = true')
                        ->andWhere('ou.office = :office')
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
        return 'todo';
    }
}
