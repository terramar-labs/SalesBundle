<?php

namespace TerraMar\Bundle\SalesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TerraMar\Bundle\SalesBundle\Entity\Office;

/**
 * Gets the associated configuration
 */
class OfficeConfigurationRepository extends EntityRepository
{
    /**
     * Gets an configuration entity for a specified Office
     *
     * @deprecated
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration
     */
    public function getConfiguration(Office $office)
    {
        return $this->findOneByOffice($office);
    }

    /**
     * Finds a configuration entity for a specified Office
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration
     */
    public function findOneByOffice(Office $office)
    {
        return $this->findOneBy(array('office' => $office->getId()));
    }
}
