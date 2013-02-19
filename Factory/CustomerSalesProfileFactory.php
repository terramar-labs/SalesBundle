<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\CustomerBundle\Entity\Customer;
use Terramar\Bundle\SalesBundle\Factory\CustomerUserFactoryInterface;
use Orkestra\Transactor\Entity\Account\SimpleAccount;
use Orkestra\Transactor\Entity\Account\PointsAccount;
use Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use Terramar\Bundle\SalesBundle\Entity\Office;

class CustomerSalesProfileFactory implements CustomerSalesProfileFactoryInterface
{
    /**
     * @var \Terramar\Bundle\SalesBundle\Factory\PaymentAccountFactoryInterface
     */
    protected $paymentAccountFactory;

    /**
     * @var \Terramar\Bundle\SalesBundle\Factory\CustomerUserFactoryInterface
     */
    protected $customerUserFactory;

    /**
     * Constructor
     *
     * @param \Terramar\Bundle\SalesBundle\Factory\PaymentAccountFactoryInterface $paymentAccountFactory
     * @param \Terramar\Bundle\SalesBundle\Factory\CustomerUserFactoryInterface $customerUserFactory
     */
    public function __construct(PaymentAccountFactoryInterface $paymentAccountFactory, CustomerUserFactoryInterface $customerUserFactory)
    {
        $this->paymentAccountFactory = $paymentAccountFactory;
        $this->customerUserFactory = $customerUserFactory;
    }

    /**
     * Creates a new CustomerSalesProfile from the given Customer
     *
     * @param \Terramar\Bundle\CustomerBundle\Entity\Customer $customer
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     * @param string|null $password
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     */
    public function create(Customer $customer, Office $office, $password = null)
    {
        $profile = new CustomerSalesProfile();
        $profile->setCustomer($customer);
        $profile->setOffice($office);

        return $this->buildProfile($profile, $password);
    }

    /**
     * Builds the given CustomerSalesProfile
     *
     * This method is called when a new CustomerSalesProfile is created.
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     * @param string|null $password
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     */
    public function buildProfile(CustomerSalesProfile $profile, $password = null)
    {
        $pointsAccount = new PointsAccount();
        $pointsAccount->setName('Points');
        $this->paymentAccountFactory->buildAccountFromCustomer($pointsAccount, $profile->getCustomer());

        $defaultAccount = new SimpleAccount();
        $defaultAccount->setAlias('Cash or check');
        $this->paymentAccountFactory->buildAccountFromCustomer($defaultAccount, $profile->getCustomer());

        $profile->addAccount($pointsAccount);
        $profile->addAccount($defaultAccount);

        $profile->setUser($this->customerUserFactory->create($profile, $password));

        return $profile;
    }
}
