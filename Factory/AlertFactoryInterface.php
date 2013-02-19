<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority;
use Terramar\Bundle\SalesBundle\Model\AssignedByInterface;
use Terramar\Bundle\SalesBundle\Model\AssignedToInterface;

interface AlertFactoryInterface
{
    /**
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     * @param string $name
     * @param string $description
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     *
     * @return \Terramar\Bundle\SalesBundle\Model\AssignedAlertInterface
     */
    function createAssignedAlert(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo, $name, $description, AlertPriority $priority);

    /**
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     * @param string $name
     * @param string $description
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     * @param \DateTime $dateDue
     *
     * @return \Terramar\Bundle\SalesBundle\Model\AssignedAlertInterface
     */
    function createAssignedToDo(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo, $name, $description, AlertPriority $priority, \DateTime $dateDue);

    /**
     * @param string $name
     * @param string $description
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Alert
     */
    function createAlert($name, $description, AlertPriority $priority);

    /**
     * @param string $name
     * @param string $description
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     * @param \DateTime $dateDue
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Alert
     */
    function createToDo($name, $description, AlertPriority $priority, \DateTime $dateDue);
}
