<?php

namespace TerraMar\Bundle\SalesBundle\Factory\AssignedAlert;

use TerraMar\Bundle\SalesBundle\Model\AssignedByInterface;
use TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use TerraMar\Bundle\SalesBundle\Entity\Alert\CustomerAlert;
use TerraMar\Bundle\SalesBundle\Entity\OfficeUser;
use TerraMar\Bundle\SalesBundle\Entity\Alert;
use TerraMar\Bundle\SalesBundle\Model\AssignedToInterface;

class CustomerAlertFactory implements AssignedAlertFactoryInterface
{
    /**
     * Creates an assigned alert
     *
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert $alert
     *
     * @return \TerraMar\Bundle\SalesBundle\Model\AssignedAlertInterface
     */
    public function createAssignedAlert(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo, Alert $alert)
    {
        return new CustomerAlert($assignedBy, $assignedTo, $alert);
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
        return $assignedBy instanceof OfficeUser && $assignedTo instanceof CustomerSalesProfile;
    }

    /**
     * Gets the unique name of this factory
     *
     * @return string
     */
    public function getName()
    {
        return 'terramar.customer';
    }
}
