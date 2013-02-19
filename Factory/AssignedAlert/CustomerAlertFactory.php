<?php

namespace Terramar\Bundle\SalesBundle\Factory\AssignedAlert;

use Terramar\Bundle\SalesBundle\Model\AssignedByInterface;
use Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use Terramar\Bundle\SalesBundle\Entity\Alert\CustomerAlert;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;
use Terramar\Bundle\SalesBundle\Entity\Alert;
use Terramar\Bundle\SalesBundle\Model\AssignedToInterface;

class CustomerAlertFactory implements AssignedAlertFactoryInterface
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
    public function createAssignedAlert(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo, Alert $alert)
    {
        return new CustomerAlert($assignedBy, $assignedTo, $alert);
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
