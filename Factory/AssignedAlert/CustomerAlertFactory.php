<?php

namespace Terramar\Bundle\SalesBundle\Factory\AssignedAlert;

use Terramar\Bundle\NotificationBundle\Factory\AssignedAlert\AssignedAlertFactoryInterface;
use Terramar\Bundle\NotificationBundle\Model\MessageInterface;
use Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use Terramar\Bundle\SalesBundle\Entity\Alert\CustomerAlert;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;

class CustomerAlertFactory implements AssignedAlertFactoryInterface
{
    /**
     * Creates an assigned alert
     *
     * @param object $assignedBy
     * @param object $assignedTo
     * @param \Terramar\Bundle\NotificationBundle\Model\MessageInterface $alert
     *
     * @return \Terramar\Bundle\NotificationBundle\Model\AssignedAlertInterface
     */
    public function createAssignedAlert($assignedBy, $assignedTo, MessageInterface $alert)
    {
        return new CustomerAlert($assignedBy, $assignedTo, $alert);
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
