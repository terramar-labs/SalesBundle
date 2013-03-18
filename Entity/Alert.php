<?php

namespace Terramar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Common\Entity\AbstractEntity;
use Terramar\Bundle\NotificationBundle\Model\Alert\AlertType;
use Terramar\Bundle\NotificationBundle\Model\Alert\AlertStatus;
use Terramar\Bundle\NotificationBundle\Model\Alert\AlertPriority;
use Terramar\Bundle\NotificationBundle\Model\TicketInterface;

/**
 * An Alert
 *
 * @ORM\Entity
 * @ORM\Table(name="terramar_alerts")
 */
class Alert extends AbstractEntity implements TicketInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    protected $description = '';

    /**
     * @var \Terramar\Bundle\NotificationBundle\Model\Alert\AlertStatus
     *
     * @ORM\Column(name="status", type="enum.terramar.notification.alert_status")
     */
    protected $status;

    /**
     * @var \Terramar\Bundle\NotificationBundle\Model\Alert\AlertPriority
     *
     * @ORM\Column(name="priority", type="enum.terramar.notification.alert_priority")
     */
    protected $priority;

    /**
     * @var \Terramar\Bundle\NotificationBundle\Model\Alert\AlertType
     *
     * @ORM\Column(name="type", type="enum.terramar.notification.alert_type")
     */
    protected $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_due", type="date", nullable=true)
     */
    protected $dateDue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_completed", type="date", nullable=true)
     */
    protected $dateCompleted;

    /**
     * @param \DateTime $dateDue
     */
    public function setDateDue(\DateTime $dateDue)
    {
        $this->dateDue = $dateDue;
    }

    /**
     * @return \DateTime
     */
    public function getDateDue()
    {
        return $this->dateDue;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = (string)$description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     */
    public function setPriority(AlertPriority $priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Alert\AlertPriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert\AlertStatus $status
     */
    public function setStatus(AlertStatus $status)
    {
        $this->status = $status;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Alert\AlertStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert\AlertType $type
     */
    public function setType(AlertType $type)
    {
        $this->type = $type;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Alert\AlertType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Marks the alert as viewed
     *
     * @return void
     */
    public function markViewed()
    {
        $this->setStatus(new AlertStatus(AlertStatus::VIEWED));
    }

    /**
     * @return \DateTime
     */
    public function getDateCompleted()
    {
        return $this->dateCompleted;
    }

    /**
     * Marks the Ticket as complete
     *
     * @return void
     */
    public function markComplete()
    {
        $this->setStatus(new AlertStatus(AlertStatus::COMPLETED));
    }
}
