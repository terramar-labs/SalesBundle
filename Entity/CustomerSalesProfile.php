<?php

namespace TerraMar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TerraMar\Bundle\SalesBundle\Model\AssignedToInterface;
use Orkestra\Transactor\Entity\Account\PointsAccount;
use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use Orkestra\Transactor\Entity\Account\SimpleAccount;
use Orkestra\Transactor\Entity\Account\CardAccount;
use Orkestra\Transactor\Entity\Account\BankAccount;
use Doctrine\Common\Collections\ArrayCollection;
use Orkestra\Transactor\Entity\AbstractAccount;
use Orkestra\Common\Entity\EntityBase;

/**
 * Information defining a customer's sales profile
 *
 * @ORM\Entity(repositoryClass="TerraMar\Bundle\SalesBundle\Repository\CustomerSalesProfileRepository")
 * @ORM\Table(name="terramar_customer_sales_profiles")
 */
class CustomerSalesProfile extends EntityBase implements AssignedToInterface
{
    /**
     * @var bool
     *
     * @ORM\Column(name="autopay", type="boolean")
     */
    protected $autopay = false;

    /**
     * @var \Orkestra\Transactor\Entity\Account\PointsAccount
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Transactor\Entity\Account\PointsAccount", cascade={"persist"})
     * @ORM\JoinColumn(name="points_account_id", referencedColumnName="id")
     */
    protected $pointsAccount;

    /**
     * @var \Orkestra\Transactor\Entity\AbstractAccount
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Transactor\Entity\AbstractAccount")
     * @ORM\JoinColumn(name="autopay_account_id", referencedColumnName="id", nullable=true)
     */
    protected $autopayAccount;

    /**
     * @var \TerraMar\Bundle\CustomerBundle\Entity\Customer
     *
     * @ORM\OneToOne(targetEntity="TerraMar\Bundle\CustomerBundle\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Office
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\Office")
     * @ORM\JoinColumn(name="office_id", referencedColumnName="id")
     */
    protected $office;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TerraMar\Bundle\SalesBundle\Entity\Contract", mappedBy="profile")
     */
    protected $contracts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Orkestra\Transactor\Entity\AbstractAccount", cascade={"persist"})
     * @ORM\JoinTable(name="terramar_customers_accounts",
     *      joinColumns={@ORM\JoinColumn(name="profile_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="account_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $accounts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TerraMar\Bundle\SalesBundle\Entity\Alert\CustomerAlert", mappedBy="alert", cascade={"persist"})
     */
    protected $alerts;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Salesperson
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\Salesperson", cascade={"persist"})
     * @ORM\JoinColumn(name="salesperson_id", referencedColumnName="id")
     */
    protected $salesperson;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\OfficeUser
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\OfficeUser", cascade={"persist"})
     * @ORM\JoinColumn(name="office_user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contracts = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->accounts = new ArrayCollection();
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $accounts
     */
    public function setAccounts($accounts)
    {
        $this->accounts = $accounts;
    }

    /**
     * @param \Orkestra\Transactor\Entity\AbstractAccount $account
     */
    public function addAccount(AbstractAccount $account)
    {
        $this->accounts[] = $account;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @param Contract $contract
     */
    public function addContract(Contract $contract)
    {
        $contract->setProfile($this);
        $this->contracts->add($contract);
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $contracts
     */
    public function setContracts($contracts)
    {
        $this->contracts = $contracts;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * @param \TerraMar\Bundle\CustomerBundle\Entity\Customer $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return \TerraMar\Bundle\CustomerBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     */
    public function setOffice(Office $office)
    {
        $this->office = $office;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Salesperson $salesperson
     */
    public function setSalesperson(Salesperson $salesperson)
    {
        $this->salesperson = $salesperson;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Salesperson
     */
    public function getSalesperson()
    {
        return $this->salesperson;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\OfficeUser $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\OfficeUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Orkestra\Transactor\Entity\AbstractAccount $account
     */
    public function setAutopayAccount(AbstractAccount $account = null)
    {
        $this->autopayAccount = $account;
    }

    /**
     * @return \Orkestra\Transactor\Entity\AbstractAccount
     */
    public function getAutopayAccount()
    {
        return $this->autopayAccount;
    }

    /**
     * @param boolean $autopay
     */
    public function setAutopay($autopay)
    {
        $this->autopay = $autopay ? true : false;
    }

    /**
     * @return boolean
     */
    public function isAutopay()
    {
        return $this->autopay;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBankAccounts()
    {
        return $this->accounts->filter(function(AbstractAccount $account) {
            return $account instanceof BankAccount;
        });
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCardAccounts()
    {
        return $this->accounts->filter(function(AbstractAccount $account) {
            return $account instanceof CardAccount;
        });
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAutopayAccounts()
    {
        return $this->accounts->filter(function(AbstractAccount $account) {
            return !$account instanceof SimpleAccount && !$account instanceof PointsAccount && $account->isActive();
        });
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSimpleAccounts()
    {
        return $this->accounts->filter(function(AbstractAccount $account) {
            return $account instanceof SimpleAccount;
        });
    }

    /**
     * @param \Orkestra\Transactor\Entity\Account\PointsAccount $pointsAccount
     */
    public function setPointsAccount(PointsAccount $pointsAccount)
    {
        $this->pointsAccount = $pointsAccount;
    }

    /**
     * @return \Orkestra\Transactor\Entity\Account\PointsAccount
     */
    public function getPointsAccount()
    {
        return $this->pointsAccount;
    }
}
