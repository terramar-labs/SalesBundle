<?php
namespace TerraMar\Bundle\SalesBundle\Entity\Alert;

use Doctrine\ORM\Mapping as ORM;
use TerraMar\Bundle\SalesBundle\Model\AssignedToInterface;
use TerraMar\Bundle\SalesBundle\Model\AssignedByInterface;
use TerraMar\Bundle\SalesBundle\Entity\OfficeUser;
use TerraMar\Bundle\SalesBundle\Model\AssignedAlertInterface;
use TerraMar\Bundle\SalesBundle\Entity\Alert;
use Orkestra\Common\Entity\EntityBase;

/**
 * An Office Alert
 *
 * @ORM\Entity(repositoryClass="TerraMar\Bundle\SalesBundle\Repository\OfficeUserAlertRepository")
 * @ORM\Table(name="terramar_office_alerts")
 */
class OfficeUserAlert extends EntityBase implements AssignedAlertInterface
{
    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Alert
     *
     * @ORM\OneToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\Alert", cascade={"persist"})
     * @ORM\JoinColumn(name="alert_id", referencedColumnName="id")
     */
    protected $alert;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\OfficeUser
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\OfficeUser")
     * @ORM\JoinColumn(name="assigned_by_user_id", referencedColumnName="id")
     */
    protected $assignedBy;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\OfficeUser
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\OfficeUser")
     * @ORM\JoinColumn(name="assigned_to_user_id", referencedColumnName="id")
     */
    protected $assignedTo;

    /**
     * Constructor
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\OfficeUser $assignedBy
     * @param \TerraMar\Bundle\SalesBundle\Entity\OfficeUser $assignedTo
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert $alert
     */
    public function __construct(OfficeUser $assignedBy = null, OfficeUser $assignedTo = null, Alert $alert = null)
    {
        $this->assignedBy = $assignedBy;
        $this->assignedTo = $assignedTo;
        $this->alert = $alert;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert $alert
     */
    public function setAlert(Alert $alert)
    {
        $this->alert = $alert;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Alert
     */
    public function getAlert()
    {
        return $this->alert;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\OfficeUser $assignedBy
     */
    public function setAssignedBy(AssignedByInterface $assignedBy)
    {
        $this->assignedBy = $assignedBy;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\OfficeUser
     */
    public function getAssignedBy()
    {
        return $this->assignedBy;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\OfficeUser $assignedTo
     */
    public function setAssignedTo(AssignedToInterface $assignedTo)
    {
        $this->assignedTo = $assignedTo;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\OfficeUser
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }



}
