<?php

namespace Terramar\Bundle\SalesBundle\Factory\AssignedAlert;

use Terramar\Bundle\SalesBundle\Model\AssignedByInterface;
use Terramar\Bundle\SalesBundle\Entity\Alert;
use Terramar\Bundle\SalesBundle\Model\AssignedToInterface;

interface AssignedAlertFactoryInterface
{
    /**
     * Creates an assigned alert
     *
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert $alert
     *
     * @return \Terramar\Bundle\SalesBundle\Model\AssignedAlertInterface
     */
    function createAssignedAlert(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo, Alert $alert);

    /**
     * Returns true if this factory supports the given assigner and assignee
     *
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
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
