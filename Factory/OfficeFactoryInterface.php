<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\Office;

interface OfficeFactoryInterface
{
    /**
     * Creates a new Office entity
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office
     */
    function create();

    /**
     * Builds the given Office
     *
     * This method is called when a new Office is created.
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office
     */
    function buildOffice(Office $office);
}
