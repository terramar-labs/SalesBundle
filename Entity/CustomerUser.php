<?php

namespace TerraMar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\CustomerBundle\Entity\Note;
use Doctrine\Common\Collections\ArrayCollection;
use TerraMar\Bundle\SalesBundle\Model\AssignedToInterface;
use TerraMar\Bundle\SalesBundle\Model\AssignedByInterface;
use Orkestra\Bundle\ApplicationBundle\Entity\User;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * A user associated with an Customer
 *
 * @ORM\Entity
 * @ORM\Table(name="terramar_customer_users")
 */
class CustomerUser extends AbstractEntity
{
    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Office
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\Office")
     * @ORM\JoinColumn(name="office_id", referencedColumnName="id")
     */
    protected $office;

    /**
     * @var \TerraMar\Bundle\CustomerBundle\Entity\Customer
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\CustomerBundle\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Entity\User
     *
     * @ORM\OneToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     * @param \TerraMar\Bundle\CustomerBundle\Entity\Customer $customer
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\User $user
     */
    public function __construct(Office $office, Customer $customer, User $user)
    {
        $this->office = $office;
        $this->customer = $customer;
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->customer ? $this->customer->__toString() : '';
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return \TerraMar\Bundle\CustomerBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }
}
