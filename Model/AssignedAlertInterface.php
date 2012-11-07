<?php

namespace TerraMar\Bundle\SalesBundle\Model;

use TerraMar\Bundle\SalesBundle\Entity\Alert;

interface AssignedAlertInterface
{
    /**
     * @return Alert
     */
    function getAlert();

    /**
     * @return AssignedByInterface
     */
    function getAssignedBy();

    /**
     * @return AssignedToInterface
     */
    function getAssignedTo();

    /**
     * @param Alert $alert
     */
    function setAlert(Alert $alert);

    /**
     * @param AssignedByInterface $assignedBy
     */
    function setAssignedBy(AssignedByInterface $assignedBy);

    /**
     * @param AssignedToInterface $assignedTo
     */
    function setAssignedTo(AssignedToInterface $assignedTo);
}
