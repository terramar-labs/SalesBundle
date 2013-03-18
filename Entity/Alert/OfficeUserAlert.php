<?php
namespace Terramar\Bundle\SalesBundle\Entity\Alert;

use Doctrine\ORM\Mapping as ORM;
use Terramar\Bundle\SalesBundle\Model\Alert\System;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;
use Terramar\Bundle\NotificationBundle\Model\AssignedAlertInterface;
use Terramar\Bundle\SalesBundle\Entity\Alert;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * An Office Alert
 *
 * @ORM\Entity(repositoryClass="Terramar\Bundle\SalesBundle\Repository\OfficeUserAlertRepository")
 * @ORM\Table(name="terramar_office_alerts")
 */
class OfficeUserAlert extends AbstractEntity implements AssignedAlertInterface
{
    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\Alert
     *
     * @ORM\OneToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\Alert", cascade={"persist"})
     * @ORM\JoinColumn(name="alert_id", referencedColumnName="id")
     */
    protected $alert;

    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\OfficeUser
     *
     * @ORM\ManyToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\OfficeUser")
     * @ORM\JoinColumn(name="assigned_by_user_id", referencedColumnName="id")
     */
    protected $assignedBy;

    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\OfficeUser
     *
     * @ORM\ManyToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\OfficeUser")
     * @ORM\JoinColumn(name="assigned_to_user_id", referencedColumnName="id")
     */
    protected $assignedTo;

    /**
     * Constructor
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\OfficeUser $assignedBy
     * @param \Terramar\Bundle\SalesBundle\Entity\OfficeUser $assignedTo
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert $alert
     */
    public function __construct(OfficeUser $assignedBy = null, OfficeUser $assignedTo = null, Alert $alert = null)
    {
        $this->assignedBy = $assignedBy;
        $this->assignedTo = $assignedTo;
        $this->alert = $alert;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert $alert
     */
    public function setAlert(Alert $alert)
    {
        $this->alert = $alert;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Alert
     */
    public function getAlert()
    {
        return $this->alert;
    }

    /**
     * @param object|\Terramar\Bundle\SalesBundle\Entity\OfficeUser|null $assignedBy
     */
    public function setAssignedBy(OfficeUser $assignedBy = null)
    {
        $this->assignedBy = $assignedBy;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\OfficeUser
     */
    public function getAssignedBy()
    {
        return $this->assignedBy ?: new System();
    }

    /**
     * @param object|\Terramar\Bundle\SalesBundle\Entity\OfficeUser $assignedTo
     */
    public function setAssignedTo(OfficeUser $assignedTo)
    {
        $this->assignedTo = $assignedTo;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\OfficeUser
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }
}
