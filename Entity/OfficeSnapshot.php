<?php

namespace TerraMar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Common\Entity\AbstractEntity;
use Orkestra\Bundle\ReportBundle\Model\SnapshotInterface;
use Orkestra\Bundle\ReportBundle\ReportInterface;

/**
 * A report snapshot in the context of an Office
 *
 * @ORM\Entity
 * @ORM\Table(name="terramar_office_snapshots")
 */
class OfficeSnapshot extends AbstractEntity implements SnapshotInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="report", type="string")
     */
    protected $report;

    /**
     * @var array
     *
     * @ORM\Column(name="facts", type="array")
     */
    protected $facts;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Office
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\Office")
     * @ORM\JoinColumn(name="office_id", referencedColumnName="id")
     */
    protected $office;

    /**
     * Constructor
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     * @param \Orkestra\Bundle\ReportBundle\ReportInterface $report
     * @param array $facts An associative array of facts
     */
    public function __construct(Office $office, ReportInterface $report, array $facts = array())
    {
        $this->office = $office;
        $this->report = $report->getName();
        $this->facts = $facts;
    }

    /**
     * Gets the name of the associated report
     *
     * @return string
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Gets the associative array of facts associated with this snapshot
     *
     * @return array
     */
    public function getFacts()
    {
        return $this->facts;
    }

    /**
     * Gets a single fact by its key
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getFact($name)
    {
        return array_key_exists($name, $this->facts) ? $this->facts[$name] : null;
    }
}
