<?php

namespace TerraMar\Bundle\SalesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TerraMar\Bundle\CustomerBundle\Entity\Customer;

class ContractRepository extends EntityRepository
{
    /**
     * @param int $id
     * @param \TerraMar\Bundle\CustomerBundle\Entity\Customer $customer
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
