<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\Office;

interface OfficeConfigurationFactoryInterface
{
    /**
     * Creates a new OfficeConfiguration entity with default values
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration
     */
    function create(Office $office);
}
