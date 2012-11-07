<?php

namespace TerraMar\Bundle\SalesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertStatus;
use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertType;
use TerraMar\Bundle\SalesBundle\Entity\OfficeUser;

class OfficeUserAlertRepository extends EntityRepository
{
    public function findToDosByAssignedTo(OfficeUser $assignedTo)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('oua')
            ->from('TerraMar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert', 'oua')
            ->join('oua.alert', 'a')
            ->where('oua.assignedTo = :assignedTo')
            ->andWhere('a.type = :type')
            ->andWhere('a.status IN (:statuses)');

        $qb->setParameters(array('assignedTo' => $assignedTo, 'type' => AlertType::TODO, 'statuses' => array(AlertStatus::IN_PROGRESS, AlertStatus::POSTED, AlertStatus::VIEWED)));
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function findActiveAlertsByAssignedTo(OfficeUser $assignedTo)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('oua')
            ->from('TerraMar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert', 'oua')
            ->join('oua.alert', 'a')
            ->where('oua.assignedTo = :assignedTo')
            ->andWhere('a.type = :type')
            ->andWhere('a.status IN (:statuses)');

        $qb->setParameters(array('assignedTo' => $assignedTo, 'type' => AlertType::ALERT, 'statuses' => array(AlertStatus::IN_PROGRESS, AlertStatus::POSTED, AlertStatus::VIEWED)));
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function findAlertHistoryByAssignedTo(OfficeUser $assignedTo)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('oua')
            ->from('TerraMar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert', 'oua')
            ->join('oua.alert', 'a')
            ->where('oua.assignedTo = :assignedTo')
            ->andWhere('a.status = :statuses')
            ->orderBy('a.dateCreated', 'DESC')
            ->setMaxResults(20);

        $qb->setParameters(array('assignedTo' => $assignedTo, 'statuses' => AlertStatus::COMPLETED));
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function findAlertHistoryByAssignedBy(OfficeUser $assignedTo)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('oua')
            ->from('TerraMar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert', 'oua')
            ->join('oua.alert', 'a')
            ->where('oua.assignedTo = :assignedTo')
            ->andWhere('a.status = :statuses')
            ->orderBy('a.dateCreated', 'DESC')
            ->setMaxResults(20);

        $qb->setParameters(array('assignedTo' => $assignedTo, 'statuses' => AlertStatus::COMPLETED));
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function findAllAlertHistoryByUser(OfficeUser $user)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('oua')
            ->from('TerraMar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert', 'oua')
            ->join('oua.alert', 'a');
        $qb->where($qb->expr()->orX(
                $qb->expr()->eq('oua.assignedTo', ':user'),
                $qb->expr()->eq('oua.assignedBy', ':user')
            ))
            ->andWhere('a.status = :statuses')
            ->orderBy('a.dateCreated', 'DESC')
            ->setMaxResults(20);

        $qb->setParameters(array('user' => $user, 'statuses' => AlertStatus::COMPLETED));
        $result = $qb->getQuery()->getResult();

        return $result;
    }

}
