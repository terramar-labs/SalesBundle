<?php
namespace Terramar\Bundle\SalesBundle\Entity\Alert;

use Doctrine\ORM\Mapping as ORM;
use Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use Terramar\Bundle\CustomerBundle\Entity\Customer;
use Terramar\Bundle\SalesBundle\Model\AssignedToInterface;
use Terramar\Bundle\SalesBundle\Model\AssignedByInterface;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;
use Terramar\Bundle\SalesBundle\Model\AssignedAlertInterface;
use Terramar\Bundle\SalesBundle\Entity\Alert;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * An alert assigned to a Customer
 *
 * @ORM\Entity(repositoryClass="Terramar\Bundle\SalesBundle\Repository\CustomerAlertRepository")
 * @ORM\Table(name="terramar_customer_alerts")
 */
class CustomerAlert extends AbstractEntity implements AssignedAlertInterface
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
     * @ORM\JoinColumn(name="assigned_by_id", referencedColumnName="id")
     */
    protected $assignedBy;

    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     *
     * @ORM\ManyToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile", inversedBy="alerts")
     * @ORM\JoinColumn(name="assigned_to_id", referencedColumnName="id")
     */
    protected $assignedTo;

    /**
     * Constructor
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\OfficeUser $assignedBy
     * @param \Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile $assignedTo
     * @param \Terramar\Bundle\SalesBundle\Entity\Alert $alert
     */
    public function __construct(OfficeUser $assignedBy = null, CustomerSalesProfile $assignedTo = null, Alert $alert = null)
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
     * @param \Terramar\Bundle\SalesBundle\Entity\OfficeUser $assignedBy
     */
    public function setAssignedBy(AssignedByInterface $assignedBy)
    {
        $this->assignedBy = $assignedBy;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\OfficeUser
     */
    public function getAssignedBy()
    {
        return $this->assignedBy;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile $assignedTo
     */
    public function setAssignedTo(AssignedToInterface $assignedTo)
    {
        $this->assignedTo = $assignedTo;
    }

    /**
     * @return \Terramar\Bundle\CustomerBundle\Entity\Customer
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }
}
