<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
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
    function fillAccountWithDetails(AbstractAccount $account, Customer $customer);
}
