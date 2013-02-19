<?php

namespace Terramar\Bundle\SalesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Terramar\Bundle\CustomerBundle\Entity\Customer;

class ContractRepository extends EntityRepository
{
    /**
     * @param int $id
     * @param \Terramar\Bundle\CustomerBundle\Entity\Customer $customer
     *
     * @return array
     */
    public function findOneByIdAndCustomer($id, Customer $customer)
    {
        return $this->createQueryBuilder('c')
            ->join('c.profile', 'p', 'WITH', 'p.customer = :customer')
            ->where('c.id = :id')
            ->setParameters(array('customer' => $customer, 'id' => $id))
            ->getQuery()
            ->getOneOrNullResult();
    }
}
