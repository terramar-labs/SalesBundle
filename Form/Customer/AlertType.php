<?php

namespace Terramar\Bundle\SalesBundle\Form\Customer;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array('label' => 'Title'))
            ->add('description')
            ->add('priority', 'enum', array(
                'label' => 'Alert Priority',
                'enum' => 'Terramar\Bundle\NotificationBundle\Model\Alert\AlertPriority'
            ));
    }

    public function getName()
    {
        return 'alert';
    }
}
