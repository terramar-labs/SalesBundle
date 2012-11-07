<?php

namespace TerraMar\Bundle\SalesBundle\Report;

use Orkestra\Bundle\ReportBundle\ReportInterface;
use TerraMar\Bundle\SalesBundle\Entity\OfficeSnapshot;
use TerraMar\Bundle\SalesBundle\Entity\Office;

abstract class AbstractOfficeReport implements ReportInterface
{
    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @throws \RuntimeException
     * @return \TerraMar\Bundle\SalesBundle\Entity\OfficeSnapshot
     */
    public function createSnapshot(Office $office = null)
    {
        if (!$office) {
            throw new \RuntimeException('Office is a required parameter');
        }

        return new OfficeSnapshot($office, $this, $this->_gather($office));
    }

    /**
     * Gathers data to use in creation of a new Snapshot
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return array An associative array of facts
     */
    abstract protected function _gather(Office $office);
}
