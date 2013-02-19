<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\SalesBundle\Entity\Office;

interface OfficeConfigurationFactoryInterface
{
    /**
     * Creates a new OfficeConfiguration entity with default values
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration
     */
    function create(Office $office);
}
