<?php

namespace TerraMar\Bundle\SalesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TerraMar\Bundle\SalesBundle\Entity\Office;
use Orkestra\Bundle\ApplicationBundle\Entity\User;

class OfficeRepository extends EntityRepository
{
    public function findOfficeByUser(User $user)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('ou')
            ->from('TerraMar\Bundle\SalesBundle\Entity\OfficeUser', 'ou')
            ->join('ou.office', 'o')
            ->where('ou.user = :user');

        $qb->setParameter('user', $user);
        $result = $qb->getQuery()->getOneOrNullResult();

        return empty($result) ? null : $result->getOffice();
    }

    public function findParentOffices()
    {
        return $this->findBy(array('active' => true, 'parent' => null));
    }

    public function findAllChildOffices()
    {
        return $this->createQueryBuilder('o')
            ->where('o.active = true')
            ->andWhere('o.parent IS NOT NULL')
            ->getQuery()
            ->getResult();
    }

    public function findChildOfficesByParent(Office $office)
    {
        return $this->findBy(array('active' => true, 'parent' => $office->getId()));
    }
}
