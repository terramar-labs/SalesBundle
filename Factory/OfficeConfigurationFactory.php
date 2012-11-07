<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\Office;
use Orkestra\Transactor\Entity\Credentials;
use TerraMar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration;

class OfficeConfigurationFactory implements OfficeConfigurationFactoryInterface
{
    /**
     * Creates a new OfficeConfiguration entity with default values
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \Pocomos\Bundle\PestManagementBundle\Entity\Office\OfficeConfiguration
     */
    public function create(Office $office)
    {
        $config = new OfficeConfiguration();
        $config->setTimezone(date_default_timezone_get());
        $credentials = new Credentials();
        $credentials->setTransactor('orkestra.generic');
        $config->setCashCredentials($credentials);
        $config->setCheckCredentials($credentials);
        $credentials = new Credentials();
        $credentials->setTransactor('orkestra.generic.points');
        $config->setPointsCredentials($credentials);
        $config->setPointsEnabled(true);
        $config->setOffice($office);

        return $config;
    }
}
