<?php

namespace Terramar\Bundle\SalesBundle\Factory\AssignedAlert;

use Terramar\Bundle\NotificationBundle\Factory\AssignedAlert\AssignedAlertFactoryInterface;
use Terramar\Bundle\NotificationBundle\Model\MessageInterface;
use Terramar\Bundle\SalesBundle\Model\Alert\System;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;
use Terramar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert;
use Terramar\Bundle\SalesBundle\Entity\Alert;

class OfficeUserAlertFactory implements AssignedAlertFactoryInterface
{
    /**
     * @param object $assignedBy
     * @param object $assignedTo
     * @param \Terramar\Bundle\NotificationBundle\Model\MessageInterface $alert
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert
     */
    public function createAssignedAlert($assignedBy, $assignedTo, MessageInterface $alert)
    {
        $officeUserAlert = new OfficeUserAlert();
        $officeUserAlert->setAssignedBy($assignedBy instanceof System ? null : $assignedBy);
        $officeUserAlert->setAssignedTo($assignedTo);
        $officeUserAlert->setAlert($alert);

        return $officeUserAlert;
    }

    /**
     * Returns true if this factory supports the given assigner and assignee
     *
     * @param object $assignedBy
     * @param object $assignedTo
     *
     * @return bool
     */
    public function supports($assignedBy, $assignedTo)
    {
        return ($assignedBy instanceof OfficeUser || $assignedBy instanceof System)
            && $assignedTo instanceof OfficeUser;
    }

    /**
     * Gets the unique name of this factory
     *
     * @return string
     */
    public function getName()
    {
        return 'terramar.office_user';
    }
}
