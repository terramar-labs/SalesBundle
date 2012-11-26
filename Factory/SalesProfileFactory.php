<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use Orkestra\Transactor\Entity\Account\SimpleAccount;
use Orkestra\Transactor\Entity\Account\PointsAccount;
use TerraMar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository;
use TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use TerraMar\Bundle\SalesBundle\Entity\Office;

class SalesProfileFactory implements SalesProfileFactoryInterface
{
    protected $paymentAccountFactory;

    public function __construct(PaymentAccountFactoryInterface $paymentAccountFactory)
    {
        $this->paymentAccountFactory = $paymentAccountFactory;
    }

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

        return $profile;
    }
}
