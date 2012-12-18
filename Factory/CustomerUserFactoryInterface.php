<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile;

/**
 * Defines the contract any CustomerUserFactory must follow
 */
interface CustomerUserFactoryInterface
{
    /**
     * Creates a new CustomerUser entity
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     * @param string|null $password
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\CustomerUser
     */
    function create(CustomerSalesProfile $profile, $password = null);

    /**
     * Updates a CustomerUser's email address
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     * @param string $email
     *
     * @return void
     */
    function updateCustomerEmail(CustomerSalesProfile $profile, $email);
}
