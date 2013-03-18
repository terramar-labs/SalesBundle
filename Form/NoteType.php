<?php

namespace Terramar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Terramar\Bundle\CustomerBundle\Entity\Note\InteractionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('summary', null, array('label' => 'Note'))
            ->add('body', null, array('label' => 'Body'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Terramar\Bundle\CustomerBundle\Entity\Note'
        ));
    }

    public function getName()
    {
        return 'office_user_note';
    }
}
