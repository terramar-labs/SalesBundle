<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\SalesBundle\Entity\Office;

class OfficeFactory implements OfficeFactoryInterface
{
    /**
     * @var \Terramar\Bundle\SalesBundle\Factory\OfficeConfigurationFactoryInterface
     */
    protected $officeConfigurationFactory;

    /**
     * @var \Terramar\Bundle\SalesBundle\Factory\OfficeSalesProfileFactoryInterface
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
     * @return \Terramar\Bundle\SalesBundle\Entity\Office
     */
    public function create()
    {
        return $this->buildOffice(new Office());
    }

    /**
     * Builds the given Office
     *
     * This method is called when a new Office is created.
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Office
     */
    public function buildOffice(Office $office)
    {
        $office->setConfiguration($this->officeConfigurationFactory->create($office));
        if (!$office->getProfile()) {
            $office->setProfile($this->officeSalesProfileFactory->create($office));
        } else {
            $this->officeSalesProfileFactory->buildProfile($office->getProfile());
        }

        return $office;
    }
}
