<?php

namespace TerraMar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertType;
use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertStatus;
use TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority;
use Orkestra\Common\Entity\EntityBase;

/**
 * An Alert
 *
 * @ORM\Entity
 * @ORM\Table(name="terramar_alerts")
 */
class Alert extends EntityBase
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
     * @var \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertStatus
     *
     * @ORM\Column(name="status", type="enum.terramar.sales.alert_status")
     */
    protected $status;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority
     *
     * @ORM\Column(name="priority", type="enum.terramar.sales.alert_priority")
     */
    protected $priority;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertType
     *
     * @ORM\Column(name="type", type="enum.terramar.sales.alert_type")
     */
    protected $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_due", type="date", nullable=true)
     */
    protected $dateDue;

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
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority $priority
     */
    public function setPriority(AlertPriority $priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertPriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertStatus $status
     */
    public function setStatus(AlertStatus $status)
    {
        $this->status = $status;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertType $type
     */
    public function setType(AlertType $type)
    {
        $this->type = $type;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Alert\AlertType
     */
    public function getType()
    {
        return $this->type;
    }
}
