<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority;
use TerraMar\Bundle\SalesBundle\Model\AssignedByInterface;
use TerraMar\Bundle\SalesBundle\Model\AssignedToInterface;

interface AlertFactoryInterface
{
    /**
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     * @param string $name
     * @param string $description
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     *
     * @return \TerraMar\Bundle\SalesBundle\Model\AssignedAlertInterface
     */
    function createAssignedAlert(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo, $name, $description, AlertPriority $priority);

    /**
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \TerraMar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     * @param string $name
     * @param string $description
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     * @param \DateTime $dateDue
     *
     * @return \TerraMar\Bundle\SalesBundle\Model\AssignedAlertInterface
     */
    function createAssignedToDo(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo, $name, $description, AlertPriority $priority, \DateTime $dateDue);

    /**
     * @param string $name
     * @param string $description
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Alert
     */
    function createAlert($name, $description, AlertPriority $priority);

    /**
     * @param string $name
     * @param string $description
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     * @param \DateTime $dateDue
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Alert
     */
    function createToDo($name, $description, AlertPriority $priority, \DateTime $dateDue);
}
