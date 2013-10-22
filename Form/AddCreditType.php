<?php

namespace Terramar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\Min;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

class AddCreditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('amount', 'number');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        /**
        $collectionConstraint = new Collection(array(
            'amount' => new Min(0)
        ));

        $resolver->setDefaults(array(
            'validation_constraint' => $collectionConstraint
        ));
        **/
    }

    public function getName()
    {
        return 'credit';
    }
}
