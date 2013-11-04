<?php

namespace Terramar\Bundle\SalesBundle\Model;

use Orkestra\Bundle\ApplicationBundle\Model\UserInterface;
use Terramar\Bundle\CustomerBundle\Model\CustomerInterface;

interface CustomerSalesProfileInterface extends SalesProfileInterface
{
    /**
     * @return CustomerInterface
     */
    public function getCustomer();

    /**
     * @param CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer);

    /**
     * @return UserInterface
     */
    public function getUser();

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user);
}
