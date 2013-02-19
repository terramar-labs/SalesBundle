<?php

namespace Terramar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Terramar\Bundle\SalesBundle\Form\Signature\SignatureTransformer;
use Terramar\Bundle\SalesBundle\Helper\FileHelper;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SignatureType extends AbstractType
{
    protected $fileHelper;

    public function __construct(FileHelper $fileHelper)
    {
        $this->fileHelper = $fileHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new SignatureTransformer($this->fileHelper));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Terramar\Bundle\SalesBundle\Entity\Contract'
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'signature';
    }
}
