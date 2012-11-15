<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use Orkestra\Transactor\Entity\Account\PointsAccount;
use TerraMar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository;
use TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use TerraMar\Bundle\SalesBundle\Entity\Office;

class SalesProfileFactory implements SalesProfileFactoryInterface
{
    public function create(Customer $customer, Office $office)
    {
        $profile = new CustomerSalesProfile();
        $profile->setCustomer($customer);
        $profile->setOffice($office);
        $profile->setPointsAccount(new PointsAccount());

        return $profile;
    }
}
