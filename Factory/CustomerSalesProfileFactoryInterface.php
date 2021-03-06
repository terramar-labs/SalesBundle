<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\CustomerBundle\Entity\Customer;
use Terramar\Bundle\SalesBundle\Entity\Office;
use Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile;

/**
 * Defines the contract any CustomerSalesProfileFactory must follow
 */
interface CustomerSalesProfileFactoryInterface
{
    /**
     * Creates a new CustomerSalesProfile from the given Customer
     *
     * @param \Terramar\Bundle\CustomerBundle\Entity\Customer $customer
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     * @param string|null $password The newly created user's password
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     */
    function create(Customer $customer, Office $office, $password = null);

    /**
     * Builds the given CustomerSalesProfile
     *
     * This method is called when a new CustomerSalesProfile is created.
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     * @param string|null $password
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     */
    function buildProfile(CustomerSalesProfile $profile, $password = null);
}
