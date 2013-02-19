<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\SalesBundle\Factory\AlertFactoryInterface;
use Terramar\Bundle\SalesBundle\Factory\AssignedAlert\AssignedAlertFactoryInterface;
use Terramar\Bundle\SalesBundle\Entity\Alert\AlertType;
use Terramar\Bundle\SalesBundle\Entity\Alert\AlertStatus;
use Terramar\Bundle\SalesBundle\Entity\Alert;
use Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority;
use Terramar\Bundle\SalesBundle\Model\AssignedToInterface;
use Terramar\Bundle\SalesBundle\Model\AssignedByInterface;

class AlertFactory implements AlertFactoryInterface
{
    /**
     * @var array|AssignedAlert\AssignedAlertFactoryInterface[]
     */
    protected $factories = array();

    /**
     * Registers an AssignedAlertFactory with this factory
     *
     * @param AssignedAlert\AssignedAlertFactoryInterface $alertFactory
     */
    public function registerAssignedAlertFactory(AssignedAlertFactoryInterface $alertFactory)
    {
        $this->factories[$alertFactory->getName()] = $alertFactory;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     * @param string $name
     * @param string $description
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     *
     * @return \Terramar\Bundle\SalesBundle\Model\AssignedAlertInterface
     */
    public function createAssignedAlert(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo, $name, $description, AlertPriority $priority)
    {
        $alert = $this->createAlert($name, $description, $priority);
        $factory = $this->getAssignedAlertFactory($assignedBy, $assignedTo);

        $assignedAlert = $factory->createAssignedAlert($assignedBy, $assignedTo, $alert);

        return $assignedAlert;
    }

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
    public function createAssignedToDo(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo, $name, $description, AlertPriority $priority, \DateTime $dateDue)
    {
        $alert = $this->createToDo($name, $description, $priority, $dateDue);
        $factory = $this->getAssignedAlertFactory($assignedBy, $assignedTo);

        $assignedAlert = $factory->createAssignedAlert($assignedBy, $assignedTo, $alert);

        return $assignedAlert;
    }

    /**
     * @param string $name
     * @param string $description
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Alert
     */
    public function createAlert($name, $description, AlertPriority $priority)
    {
        $alert = new Alert();
        $alert->setName($name);
        $alert->setDescription($description);
        $alert->setPriority($priority);
        $alert->setStatus(new AlertStatus(AlertStatus::POSTED));
        $alert->setType(new AlertType(AlertType::ALERT));

        return $alert;
    }

    /**
     * @param string $name
     * @param string $description
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     * @param \DateTime $dateDue
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Alert
     */
    public function createToDo($name, $description, AlertPriority $priority, \DateTime $dateDue)
    {
        $alert = new Alert();
        $alert->setName($name);
        $alert->setDescription($description);
        $alert->setPriority($priority);
        $alert->setStatus(new AlertStatus(AlertStatus::POSTED));
        $alert->setType(new AlertType(AlertType::TODO));
        $alert->setDateDue($dateDue);

        return $alert;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedByInterface $assignedBy
     * @param \Terramar\Bundle\SalesBundle\Model\AssignedToInterface $assignedTo
     *
     * @return AssignedAlert\AssignedAlertFactoryInterface
     * @throws \RuntimeException
     */
    private function getAssignedAlertFactory(AssignedByInterface $assignedBy, AssignedToInterface $assignedTo)
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($assignedBy, $assignedTo)) {
                return $factory;
            }
        }

        throw new \RuntimeException('Unable to locate factory that supports the given parameters');
    }
}
