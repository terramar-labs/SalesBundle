<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\SalesBundle\Entity\Office;
use Orkestra\Transactor\Entity\AbstractAccount;

interface PaymentAccountFactoryInterface
{
    /**
     * Fills an Orkestra Account with personal details of the given Customer
     *
     * @param \Orkestra\Transactor\Entity\AbstractAccount $account
     * @param \TerraMar\Bundle\CustomerBundle\Entity\Customer $customer
     *
     * @return \Orkestra\Transactor\Entity\AbstractAccount
     */
    function buildAccountFromCustomer(AbstractAccount $account, Customer $customer);

    /**
     * Fills an Orkestra Account with personal details of the given Office
     *
     * @param \Orkestra\Transactor\Entity\AbstractAccount $account
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \Orkestra\Transactor\Entity\AbstractAccount
     */
    function buildAccountFromOffice(AbstractAccount $account, Office $office);
}
