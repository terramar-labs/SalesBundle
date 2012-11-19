<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\Office;

class OfficeFactory implements OfficeFactoryInterface
{
    /**
     * @var OfficeConfigurationFactoryInterface
     */
    protected $officeConfigurationFactory;

    /**
     * Constructor
     *
     * @param OfficeConfigurationFactoryInterface $officeConfigurationFactory
     */
    public function __construct(OfficeConfigurationFactoryInterface $officeConfigurationFactory)
    {
        $this->officeConfigurationFactory = $officeConfigurationFactory;
    }

    /**
     * Creates a new Office entity
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office
     */
    public function create()
    {
        return $this->buildOffice(new Office());
    }

    /**
     * Builds a new Office
     *
     * This method is called when a new Office is created.
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office
     */
    public function buildOffice(Office $office)
    {
        $office->setConfiguration($this->officeConfigurationFactory->create($office));

        return $office;
    }
}
