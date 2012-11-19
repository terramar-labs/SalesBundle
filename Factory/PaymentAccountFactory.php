<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use Orkestra\Transactor\Entity\AbstractAccount;

class PaymentAccountFactory implements PaymentAccountFactoryInterface
{
    /**
     * @param \Orkestra\Transactor\Entity\AbstractAccount $account
     * @param \TerraMar\Bundle\CustomerBundle\Entity\Customer $customer
     *
     * @return \Orkestra\Transactor\Entity\AbstractAccount
     */
    public function fillAccountWithDetails(AbstractAccount $account, Customer $customer)
    {
        $address = $customer->getBillingAddress();

        if (!$address) {
            $address = $customer->getContactAddress();
        }

        $account->setName($customer->__toString());
        $account->setAddress(trim($address->getStreet() . ' ' . $address->getSuite()));
        $account->setCity($address->getCity());
        $account->setRegion($address->getRegion()->getCode());
        $account->setCountry($address->getRegion()->getCountry()->getCode());
        $account->setPhoneNumber($address->getPhone());

        return $account;
    }
}
