<?php

namespace Terramar\Bundle\SalesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Terramar\Bundle\SalesBundle\Entity\Office;

class OfficeConfigurationRepository extends EntityRepository
{
    /**
     * Finds a configuration entity for a specified Office
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration
     */
    public function findOneByOffice(Office $office)
    {
        return $this->findOneBy(array('office' => $office->getId()));
    }
}
