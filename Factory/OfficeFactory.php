<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\Office;

class OfficeFactory implements OfficeFactoryInterface
{
    /**
     * Creates a new Office entity
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office
     */
    public function create()
    {
        return new Office();
    }
}
