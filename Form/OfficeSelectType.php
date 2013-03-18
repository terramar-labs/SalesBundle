<?php

namespace Terramar\Bundle\SalesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Orkestra\Bundle\ApplicationBundle\Entity\Group;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Terramar\Bundle\SalesBundle\Entity\Office;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;


class OfficeSelectType extends AbstractType
{
    protected $office;

    protected $user;

    public function __construct(Office $office, OfficeUser $user)
    {
        $this->office = $office;
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = $this->user->getUser()->getRoles();
        $roles = array_map(function (Group $group) {
            return $group->getRole();
        }, $roles);
        $office = $this->user->getOffice();

        $builder->add('office', 'entity', array(
            'class' => 'Terramar\Bundle\SalesBundle\Entity\Office',
            'query_builder' => function (EntityRepository $er) use ($roles, $office) {
                $qb = $er->createQueryBuilder('o')->where('o.active = true');

                if (!in_array('ROLE_ADMIN', $roles)) {
                    $array = array('o = :office');
                    if (in_array('ROLE_OWNER', $roles)) {
                        $array[] = 'o.parent = :office';
                    }

                    $expr = $qb->expr();
                    $qb->andWhere(call_user_func_array(array($expr, 'orX'), $array))
                        ->setParameter('office', $office);
                }

                return $qb;
            }
        ));
    }


    public function getName()
    {
        return 'office_select';
    }
}
