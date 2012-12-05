<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\SalesBundle\Entity\Office;

/**
 * Defines the contract any OfficeSalesProfileFactory must follow
 */
interface OfficeSalesProfileFactoryInterface
{
    /**
     * Creates a new OfficeSalesProfile from the given Office
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile
     */
    function create(Office $office);
}
