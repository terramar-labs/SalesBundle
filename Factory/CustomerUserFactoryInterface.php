<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile;

/**
 * Defines the contract any CustomerUserFactory must follow
 */
interface CustomerUserFactoryInterface
{
    /**
     * Creates a new CustomerUser entity
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     * @param string|null $password
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\CustomerUser
     */
    function create(CustomerSalesProfile $profile, $password = null);

    /**
     * Updates a CustomerUser's email address
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     * @param string $email
     *
     * @return void
     */
    function updateCustomerEmail(CustomerSalesProfile $profile, $email);
}
