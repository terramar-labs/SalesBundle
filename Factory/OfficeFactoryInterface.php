<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\SalesBundle\Entity\Office;

interface OfficeFactoryInterface
{
    /**
     * Creates a new Office entity
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Office
     */
    function create();

    /**
     * Builds the given Office
     *
     * This method is called when a new Office is created.
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Office
     */
    function buildOffice(Office $office);
}
