<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\SalesBundle\Entity\Office;

interface SalesProfileFactoryInterface
{
    function create(Customer $customer, Office $office);
}
