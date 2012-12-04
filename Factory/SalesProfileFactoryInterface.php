<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\SalesBundle\Entity\Office;

/**
 * Defines the contract any SalesProfileFactory must follow
 */
interface SalesProfileFactoryInterface
{
    /**
     * Creates a new CustomerSalesProfile from the given Customer
     *
     * @param \TerraMar\Bundle\CustomerBundle\Entity\Customer $customer
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     */
    function create(Customer $customer, Office $office);
}
