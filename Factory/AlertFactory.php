<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\NotificationBundle\Factory\AlertFactory as BaseFactory;
use Terramar\Bundle\NotificationBundle\Factory\AlertFactoryInterface;
use Terramar\Bundle\NotificationBundle\Factory\AssignedAlert\AssignedAlertFactoryInterface;
use Terramar\Bundle\NotificationBundle\Model\Alert\AlertPriority;
use Terramar\Bundle\NotificationBundle\Model\Alert\AlertStatus;
use Terramar\Bundle\NotificationBundle\Model\Alert\AlertType;
use Terramar\Bundle\SalesBundle\Entity\Alert;

/**
 * Overridden implementation to return Sales Bundle Alert
 */
class AlertFactory extends BaseFactory
{
    /**
     * @param string $name
     * @param string $description
     * @param \Terramar\Bundle\NotificationBundle\Model\Alert\AlertPriority $priority
     *
     * @return \Terramar\Bundle\NotificationBundle\Entity\Alert
     */
    public function createAlert($name, $description, AlertPriority $priority)
    {
        $alert = new Alert();
        $alert->setType(new AlertType(AlertType::ALERT));
        $alert->setName($name);
        $alert->setDescription($description);
        $alert->setPriority($priority);
        $alert->setStatus(new AlertStatus(AlertStatus::POSTED));

        return $alert;
    }

    /**
     * @param string $name
     * @param string $description
     *
     * @return \Terramar\Bundle\NotificationBundle\Model\AlertInterface
     */
    public function createMessage($name, $description)
    {
        $alert = new Alert();
        $alert->setType(new AlertType(AlertType::MESSAGE));
        $alert->setName($name);
        $alert->setDescription($description);
        $alert->setStatus(new AlertStatus(AlertStatus::POSTED));

        return $alert;
    }

    /**
     * @param string $name
     * @param string $description
     * @param \Terramar\Bundle\NotificationBundle\Model\Alert\AlertPriority $priority
     * @param \DateTime $dateDue
     *
     * @return \Terramar\Bundle\NotificationBundle\Entity\Ticket
     */
    public function createTicket($name, $description, AlertPriority $priority, \DateTime $dateDue)
    {
        $alert = new Alert();
        $alert->setType(new AlertType(AlertType::TICKET));
        $alert->setName($name);
        $alert->setDescription($description);
        $alert->setPriority($priority);
        $alert->setStatus(new AlertStatus(AlertStatus::POSTED));
        $alert->setDateDue($dateDue);

        return $alert;
    }
}
