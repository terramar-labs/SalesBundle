<?php

namespace Terramar\Bundle\SalesBundle\Factory\AssignedAlert;

use Terramar\Bundle\SalesBundle\Model\Alert\System;
use Terramar\Bundle\SalesBundle\Model\AssignedToInterface;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;
use Terramar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert;
use Terramar\Bundle\SalesBundle\Entity\Alert;
use Terramar\Bundle\SalesBundle\Model\AssignedByInterface;

class OfficeUserAlertFactory implements AssignedAlertFactoryInterface
{
    /**
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert $alert
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert
     */
    public function createAssignedAlert(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo, Alert $alert)
    {
        $officeUserAlert = new OfficeUserAlert();
        $officeUserAlert->setAssignedBy($assignedBy);
        $officeUserAlert->setAssignedTo($assignedTo);
        $officeUserAlert->setAlert($alert);

        return $officeUserAlert;
    }

    /**
     * Returns true if this factory supports the given assigner and assignee
     *
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     *
     * @return bool
     */
    public function supports(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo)
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
