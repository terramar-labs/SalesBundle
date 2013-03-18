<?php

namespace Terramar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Orkestra\Bundle\ApplicationBundle\Form\PreferencesType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    protected $includePassword = true;

    public function __construct($includePassword = true)
    {
        $this->includePassword = $includePassword;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username')
            ->add('email', 'email');

        if ($this->includePassword) {
            $builder->add('password', 'repeated', array(
                'type' => 'password',
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Confirm Password'),
                'invalid_message' => 'The passwords must match'
            ));
        }

        $builder->add('firstName', null, array('label' => 'First Name'))
                ->add('lastName', null, array('label' => 'Last Name'))
                ->add('groups', null, array(
                    'required' => false,
                    'multiple' => false,
                    'label' => 'Assign user admin privileges?',
                    'empty_value' => 'No administration privileges',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('g')
                            ->where('g.role NOT IN (:exclude)')
                            ->setParameter('exclude', array('ROLE_CUSTOMER', 'ROLE_TECHNICIAN', 'ROLE_SALESPERSON'));
                    },
                ))
                ->add('technician', 'checkbox', array('property_path' => false, 'required' => false))
                ->add('salesperson', 'checkbox', array('property_path' => false, 'required' => false))
                ->add('licensenumber', 'text', array('property_path' => false, 'required' => false, 'label' => 'License #'))
                ->add('preferences', new PreferencesType());
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Orkestra\Bundle\ApplicationBundle\Entity\User',
        );
    }

    public function getName()
    {
        return 'user';
    }
}
