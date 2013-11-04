<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Orkestra\Bundle\ApplicationBundle\Model\UserInterface;
use Terramar\Bundle\SalesBundle\Model\CustomerSalesProfileInterface;

/**
 * Defines the contract any CustomerUserFactory must follow
 */
interface CustomerUserFactoryInterface
{
    /**
     * Creates a new User for the given CustomerSalesProfile entity
     *
     * @param CustomerSalesProfileInterface $profile
     * @param string|null $password
     *
     * @return UserInterface
     */
    public function create(CustomerSalesProfileInterface $profile, $password = null);

    /**
     * Updates a CustomerUser's email address
     *
     * @param CustomerSalesProfileInterface $profile
     * @param string $email
     *
     * @return void
     */
    public function updateCustomerEmail(CustomerSalesProfileInterface $profile, $email);
}
