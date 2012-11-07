<?php

namespace TerraMar\Bundle\SalesBundle\Factory\AssignedAlert;

use TerraMar\Bundle\SalesBundle\Model\AssignedToInterface;
use TerraMar\Bundle\SalesBundle\Entity\OfficeUser;
use TerraMar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert;
use TerraMar\Bundle\SalesBundle\Entity\Alert;
use TerraMar\Bundle\SalesBundle\Model\AssignedByInterface;

class OfficeUserAlertFactory implements AssignedAlertFactoryInterface
{
    /**
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert $alert
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert
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
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     *
     * @return bool
     */
    public function supports(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo)
    {
        return $assignedBy instanceof OfficeUser && $assignedTo instanceof OfficeUser;
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
