<?php

namespace TerraMar\Bundle\SalesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TerraMar\Bundle\SalesBundle\Entity\Office;

class OfficeConfigurationRepository extends EntityRepository
{
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
