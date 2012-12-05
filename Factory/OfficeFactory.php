<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\Office;

class OfficeFactory implements OfficeFactoryInterface
{
    /**
     * @var \TerraMar\Bundle\SalesBundle\Factory\OfficeConfigurationFactoryInterface
     */
    protected $officeConfigurationFactory;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Factory\OfficeSalesProfileFactoryInterface
     */
    protected $officeSalesProfileFactory;

    /**
     * Constructor
     *
     * @param OfficeConfigurationFactoryInterface $officeConfigurationFactory
     * @param OfficeSalesProfileFactoryInterface $officeSalesProfileFactory
     */
    public function __construct(
        OfficeConfigurationFactoryInterface $officeConfigurationFactory,
        OfficeSalesProfileFactoryInterface $officeSalesProfileFactory
    ) {
        $this->officeConfigurationFactory = $officeConfigurationFactory;
        $this->officeSalesProfileFactory  = $officeSalesProfileFactory;
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
        $office->setProfile($this->officeSalesProfileFactory->create($office));

        return $office;
    }
}
