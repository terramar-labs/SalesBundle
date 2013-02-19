<?php

namespace Terramar\Bundle\SalesBundle\Report;

use Orkestra\Bundle\ReportBundle\ReportInterface;
use Terramar\Bundle\SalesBundle\Entity\OfficeSnapshot;
use Terramar\Bundle\SalesBundle\Entity\Office;

abstract class AbstractOfficeReport implements ReportInterface
{
    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @throws \RuntimeException
     * @return \Terramar\Bundle\SalesBundle\Entity\OfficeSnapshot
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
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return array An associative array of facts
     */
    abstract protected function _gather(Office $office);
}
