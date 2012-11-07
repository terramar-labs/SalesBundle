<?php

namespace TerraMar\Bundle\SalesBundle\Factory\AssignedAlert;

use TerraMar\Bundle\SalesBundle\Model\AssignedByInterface;
use TerraMar\Bundle\SalesBundle\Entity\Alert;
use TerraMar\Bundle\SalesBundle\Model\AssignedToInterface;

interface AssignedAlertFactoryInterface
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
    function createAssignedAlert(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo, Alert $alert);

    /**
     * Returns true if this factory supports the given assigner and assignee
     *
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     *
     * @return bool
     */
    function supports(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo);

    /**
     * Gets the unique name of this factory
     *
     * @return string
     */
    function getName();
}
