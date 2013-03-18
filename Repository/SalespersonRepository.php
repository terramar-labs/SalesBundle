<?php

namespace Terramar\Bundle\SalesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Terramar\Bundle\SalesBundle\Entity\Office;

class SalespersonRepository extends EntityRepository
{
    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return array
     */
    public function findByOffice(Office $office)
    {
        return $this->getFindByOfficeQueryBuilder($office)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getFindByOfficeQueryBuilder(Office $office)
    {
        return $this->createQueryBuilder('s')
            ->join('s.user', 'ou', 'WITH', 'ou.office = :office')
            ->where('s.active = true')
            ->setParameter('office', $office);
    }
}
