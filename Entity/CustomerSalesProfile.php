<?php

namespace Terramar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Terramar\Bundle\SalesBundle\Model\SalesProfileInterface;
use Orkestra\Transactor\Entity\Account\PointsAccount;
use Terramar\Bundle\CustomerBundle\Entity\Customer;
use Orkestra\Transactor\Entity\Account\SimpleAccount;
use Orkestra\Transactor\Entity\Account\CardAccount;
use Orkestra\Transactor\Entity\Account\BankAccount;
use Doctrine\Common\Collections\ArrayCollection;
use Orkestra\Transactor\Entity\AbstractAccount;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * Information defining a customer's sales profile
 *
 * @ORM\Entity(repositoryClass="Terramar\Bundle\SalesBundle\Repository\CustomerSalesProfileRepository")
 * @ORM\Table(name="terramar_customer_sales_profiles")
 */
class CustomerSalesProfile extends AbstractEntity implements SalesProfileInterface
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
     * @ORM\ManyToOne(targetEntity="Orkestra\Transactor\Entity\AbstractAccount", cascade={"persist"})
     * @ORM\JoinColumn(name="autopay_account_id", referencedColumnName="id", nullable=true)
     */
    protected $autopayAccount;

    /**
     * @var \Terramar\Bundle\CustomerBundle\Entity\Customer
     *
     * @ORM\OneToOne(targetEntity="Terramar\Bundle\CustomerBundle\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\Office
     *
     * @ORM\ManyToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\Office")
     * @ORM\JoinColumn(name="office_id", referencedColumnName="id")
     */
    protected $office;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Terramar\Bundle\SalesBundle\Entity\Contract", cascade={"persist"})
     * @ORM\JoinTable(name="terramar_customer_sales_profiles_contracts",
     *      joinColumns={@ORM\JoinColumn(name="profile_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="contract_id", referencedColumnName="id", unique=true)}
     * )
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
     * @ORM\OneToMany(targetEntity="Terramar\Bundle\SalesBundle\Entity\Alert\CustomerAlert", mappedBy="alert", cascade={"persist"})
     */
    protected $alerts;

    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\CustomerUser
     *
     * @ORM\ManyToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\CustomerUser", cascade={"persist"})
     * @ORM\JoinColumn(name="customer_user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contracts = new ArrayCollection();
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
        if ($account instanceof PointsAccount) {
            $this->pointsAccount = $account;
        }

        $this->accounts->add($account);
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
     * @param \Terramar\Bundle\CustomerBundle\Entity\Customer $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return \Terramar\Bundle\CustomerBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     */
    public function setOffice(Office $office)
    {
        $this->office = $office;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Office
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\CustomerUser $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\CustomerUser
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
        if (null !== $account && !$this->accounts->contains($account)) {
            $this->accounts->add($account);
        }
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
     * @return \Orkestra\Transactor\Entity\Account\PointsAccount
     */
    public function getPointsAccount()
    {
        return $this->pointsAccount;
    }
}
