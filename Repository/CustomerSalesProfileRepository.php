<?php

namespace Terramar\Bundle\SalesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;
use Terramar\Bundle\CustomerBundle\Entity\Customer;

class CustomerSalesProfileRepository extends EntityRepository
{
    public function findOneByCustomer(Customer $customer)
    {
        return $this->createQueryBuilder('csp')
            ->where('csp.customer = :customer')
            ->setParameter('customer', $customer)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByUser(OfficeUser $user)
    {
        return $this->createQueryBuilder('csp')
            ->where('csp.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
