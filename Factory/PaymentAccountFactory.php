<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\SalesBundle\Entity\Office;
use Orkestra\Transactor\Entity\AbstractAccount;

class PaymentAccountFactory implements PaymentAccountFactoryInterface
{
    /**
     * @param \Orkestra\Transactor\Entity\AbstractAccount $account
     * @param \TerraMar\Bundle\CustomerBundle\Entity\Customer $customer
     *
     * @return \Orkestra\Transactor\Entity\AbstractAccount
     */
    public function buildAccountFromCustomer(AbstractAccount $account, Customer $customer)
    {
        $address = $customer->getBillingAddress();

        if (!$address) {
            $address = $customer->getContactAddress();
        }

        if ($address) {
            $account->setAddress(trim($address->getStreet() . ' ' . $address->getSuite()));
            $account->setCity($address->getCity());
            $account->setRegion($address->getRegion()->getCode());
            $account->setCountry($address->getRegion()->getCountry()->getCode());
            $account->setPhoneNumber($address->getPhone());
        }

        $account->setName($customer->__toString());

        return $account;
    }

    /**
     * Fills an Orkestra Account with personal details of the given Office
     *
     * @param \Orkestra\Transactor\Entity\AbstractAccount $account
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \Orkestra\Transactor\Entity\AbstractAccount
     */
    public function buildAccountFromOffice(AbstractAccount $account, Office $office)
    {
        $address = $office->getContactAddress();

        if ($address) {
            $account->setAddress(trim($address->getStreet() . ' ' . $address->getSuite()));
            $account->setCity($address->getCity());
            $account->setRegion($address->getRegion()->getCode());
            $account->setCountry($address->getRegion()->getCountry()->getCode());
            $account->setPhoneNumber($address->getPhone());
        }

        $account->setName($office->getContactName());

        return $account;
    }
}
