<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\SalesBundle\Factory\CustomerUserFactoryInterface;
use Orkestra\Transactor\Entity\Account\SimpleAccount;
use Orkestra\Transactor\Entity\Account\PointsAccount;
use TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use TerraMar\Bundle\SalesBundle\Entity\Office;

class SalesProfileFactory implements SalesProfileFactoryInterface
{
    /**
     * @var \TerraMar\Bundle\SalesBundle\Factory\PaymentAccountFactoryInterface
     */
    protected $paymentAccountFactory;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Factory\CustomerUserFactoryInterface
     */
    protected $customerUserFactory;

    /**
     * Constructor
     *
     * @param \TerraMar\Bundle\SalesBundle\Factory\PaymentAccountFactoryInterface $paymentAccountFactory
     * @param \TerraMar\Bundle\SalesBundle\Factory\CustomerUserFactoryInterface $customerUserFactory
     */
    public function __construct(PaymentAccountFactoryInterface $paymentAccountFactory, CustomerUserFactoryInterface $customerUserFactory)
    {
        $this->paymentAccountFactory = $paymentAccountFactory;
        $this->customerUserFactory = $customerUserFactory;
    }

    /**
     * Creates a new CustomerSalesProfile from the given Customer
     *
     * @param \TerraMar\Bundle\CustomerBundle\Entity\Customer $customer
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     */
    public function create(Customer $customer, Office $office)
    {
        $profile = new CustomerSalesProfile();
        $profile->setCustomer($customer);
        $profile->setOffice($office);

        $pointsAccount = new PointsAccount();
        $pointsAccount->setName('Points');
        $this->paymentAccountFactory->fillAccountWithDetails($pointsAccount, $customer);

        $defaultAccount = new SimpleAccount();
        $defaultAccount->setAlias('Cash or check');
        $this->paymentAccountFactory->fillAccountWithDetails($defaultAccount, $customer);

        $profile->addAccount($pointsAccount);
        $profile->addAccount($defaultAccount);

        $profile->setUser($this->customerUserFactory->create($profile));

        return $profile;
    }
}
