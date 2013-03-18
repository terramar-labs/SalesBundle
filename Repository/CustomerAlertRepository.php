<?php

namespace Terramar\Bundle\SalesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Terramar\Bundle\NotificationBundle\Model\Alert\AlertStatus;
use Terramar\Bundle\NotificationBundle\Model\Alert\AlertType;
use Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile;

class CustomerAlertRepository extends EntityRepository
{
    public function findActiveAlertsByProfile(CustomerSalesProfile $profile)
    {
        return $this->createQueryBuilder('ca')
            ->addSelect('a')
            ->addSelect('ou')
            ->join('ca.alert', 'a')
            ->join('ca.assignedBy', 'ou')
            ->join('ca.assignedTo', 'p', 'WITH', 'p = :profile')
            ->andWhere('a.type = :type')
            ->andWhere('a.status IN (:statuses)')
            ->setParameters(array(
                'profile' => $profile,
                'type' => AlertType::ALERT,
                'statuses' => array(AlertStatus::POSTED, AlertStatus::IN_PROGRESS, AlertStatus::VIEWED)
            ))
            ->getQuery()
            ->getResult();
    }

    public function findActiveToDosByProfile(CustomerSalesProfile $profile)
    {
        return $this->createQueryBuilder('ca')
            ->addSelect('a')
            ->addSelect('ou')
            ->join('ca.alert', 'a')
            ->join('ca.assignedBy', 'ou')
            ->join('ca.assignedTo', 'p', 'WITH', 'p = :profile')
            ->andWhere('a.type = :type')
            ->andWhere('a.status IN (:statuses)')
            ->setParameters(array(
                'profile' => $profile,
                'type' => AlertType::TICKET,
                'statuses' => array(AlertStatus::POSTED, AlertStatus::IN_PROGRESS, AlertStatus::VIEWED)
            ))
            ->getQuery()
            ->getResult();
    }

    public function findAlertHistorybyProfile(CustomerSalesProfile $profile)
    {
        return $this->createQueryBuilder('ca')
            ->addSelect('a')
            ->addSelect('ou')
            ->join('ca.alert', 'a')
            ->join('ca.assignedBy', 'ou')
            ->join('ca.assignedTo', 'p', 'WITH', 'p = :profile')
            ->andWhere('a.status IN (:statuses)')
            ->setParameters(array(
                'profile' => $profile,
                'statuses' => array(AlertStatus::COMPLETED)
            ))
            ->getQuery()
            ->getResult();
    }
}
