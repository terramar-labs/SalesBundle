<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\SalesBundle\Entity\Office;
use TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile;

/**
 * Defines the contract any CustomerSalesProfileFactory must follow
 */
interface CustomerSalesProfileFactoryInterface
{
    /**
     * Creates a new CustomerSalesProfile from the given Customer
     *
     * @param \TerraMar\Bundle\CustomerBundle\Entity\Customer $customer
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     * @param string|null $password The newly created user's password
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     */
    function create(Customer $customer, Office $office, $password = null);

    /**
     * Builds the given CustomerSalesProfile
     *
     * This method is called when a new CustomerSalesProfile is created.
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     * @param string|null $password
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     */
    function buildProfile(CustomerSalesProfile $profile, $password = null);
}
