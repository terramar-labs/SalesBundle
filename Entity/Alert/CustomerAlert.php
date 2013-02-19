<?php
namespace TerraMar\Bundle\SalesBundle\Entity\Alert;

use Doctrine\ORM\Mapping as ORM;
use TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\SalesBundle\Model\AssignedToInterface;
use TerraMar\Bundle\SalesBundle\Model\AssignedByInterface;
use TerraMar\Bundle\SalesBundle\Entity\OfficeUser;
use TerraMar\Bundle\SalesBundle\Model\AssignedAlertInterface;
use TerraMar\Bundle\SalesBundle\Entity\Alert;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * An alert assigned to a Customer
 *
 * @ORM\Entity(repositoryClass="TerraMar\Bundle\SalesBundle\Repository\CustomerAlertRepository")
 * @ORM\Table(name="terramar_customer_alerts")
 */
class CustomerAlert extends AbstractEntity implements AssignedAlertInterface
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
     * @ORM\JoinColumn(name="assigned_by_id", referencedColumnName="id")
     */
    protected $assignedBy;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile")
     * @ORM\JoinColumn(name="assigned_to_id", referencedColumnName="id")
     */
    protected $assignedTo;

    /**
     * Constructor
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\OfficeUser $assignedBy
     * @param \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile $assignedTo
     * @param \TerraMar\Bundle\SalesBundle\Entity\Alert $alert
     */
    public function __construct(OfficeUser $assignedBy = null, CustomerSalesProfile $assignedTo = null, Alert $alert = null)
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
     * @param \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile $assignedTo
     */
    public function setAssignedTo(AssignedToInterface $assignedTo)
    {
        $this->assignedTo = $assignedTo;
    }

    /**
     * @return \TerraMar\Bundle\CustomerBundle\Entity\Customer
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }
}
