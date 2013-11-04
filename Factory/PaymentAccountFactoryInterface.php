<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\CustomerBundle\Entity\Customer;
use Terramar\Bundle\CustomerBundle\Model\CustomerInterface;
use Terramar\Bundle\SalesBundle\Entity\Office;
use Orkestra\Transactor\Entity\AbstractAccount;

interface PaymentAccountFactoryInterface
{
    /**
     * Fills an Orkestra Account with personal details of the given Customer
     *
     * @param \Orkestra\Transactor\Entity\AbstractAccount             $account
     * @param \Terramar\Bundle\CustomerBundle\Model\CustomerInterface $customer
     *
     * @return \Orkestra\Transactor\Entity\AbstractAccount
     */
    public function buildAccountFromCustomer(AbstractAccount $account, CustomerInterface $customer);

    /**
     * Fills an Orkestra Account with personal details of the given Office
     *
     * @param \Orkestra\Transactor\Entity\AbstractAccount $account
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \Orkestra\Transactor\Entity\AbstractAccount
     */
    public function buildAccountFromOffice(AbstractAccount $account, Office $office);
}
