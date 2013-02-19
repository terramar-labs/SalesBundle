<?php

namespace TerraMar\Bundle\SalesBundle\Entity\Office;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Transactor\Entity\Credentials;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * An office's application settings
 *
 * @ORM\Entity(repositoryClass="TerraMar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository")
 * @ORM\Table(name="terramar_office_settings")
 */
class OfficeConfiguration extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="timezone", type="string")
     */
    protected $timezone;

    /**
     * @var bool
     *
     * @ORM\Column(name="enable_points", type="boolean")
     */
    protected $pointsEnabled = true;

    /**
     * @var \Orkestra\Transactor\Entity\Credentials
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Transactor\Entity\Credentials", cascade={"persist"})
     * @ORM\JoinColumn(name="cash_credentials_id", referencedColumnName="id", nullable=true)
     */
    protected $cashCredentials;

    /**
     * @var \Orkestra\Transactor\Entity\Credentials
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Transactor\Entity\Credentials", cascade={"persist"})
     * @ORM\JoinColumn(name="check_credentials_id", referencedColumnName="id", nullable=true)
     */
    protected $checkCredentials;

    /**
     * @var \Orkestra\Transactor\Entity\Credentials
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Transactor\Entity\Credentials", cascade={"persist"})
     * @ORM\JoinColumn(name="ach_credentials_id", referencedColumnName="id", nullable=true)
     */
    protected $achCredentials;

    /**
     * @var \Orkestra\Transactor\Entity\Credentials
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Transactor\Entity\Credentials", cascade={"persist"})
     * @ORM\JoinColumn(name="card_credentials_id", referencedColumnName="id", nullable=true)
     */
    protected $cardCredentials;

    /**
     * @var \Orkestra\Transactor\Entity\Credentials
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Transactor\Entity\Credentials", cascade={"persist"})
     * @ORM\JoinColumn(name="points_credentials_id", referencedColumnName="id", nullable=true)
     */
    protected $pointsCredentials;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Office
     *
     * @ORM\OneToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\Office", inversedBy="configuration")
     * @ORM\JoinColumn(name="office_id", referencedColumnName="id")
     */
    protected $office;

    /**
     * @param boolean $pointsEnabled
     */
    public function setPointsEnabled($pointsEnabled)
    {
        $this->pointsEnabled = $pointsEnabled ? true : false;
    }

    /**
     * @return boolean
     */
    public function getPointsEnabled()
    {
        return $this->pointsEnabled;
    }

    /**
     * @return boolean
     */
    public function isPointsEnabled()
    {
        return $this->pointsEnabled;
    }

    /**
     * @param \Orkestra\Transactor\Entity\Credentials $pointsCredentials
     */
    public function setPointsCredentials(Credentials $pointsCredentials)
    {
        $this->pointsCredentials = $pointsCredentials;
    }

    /**
     * @return \Orkestra\Transactor\Entity\Credentials
     */
    public function getPointsCredentials()
    {
        return $this->pointsCredentials;
    }

    /**
     * @param \Orkestra\Transactor\Entity\Credentials $achCredentials
     */
    public function setAchCredentials(Credentials $achCredentials)
    {
        $this->achCredentials = $achCredentials;
    }

    /**
     * @return \Orkestra\Transactor\Entity\Credentials
     */
    public function getAchCredentials()
    {
        return $this->achCredentials;
    }

    /**
     * @param \Orkestra\Transactor\Entity\Credentials $cardCredentials
     */
    public function setCardCredentials(Credentials $cardCredentials)
    {
        $this->cardCredentials = $cardCredentials;
    }

    /**
     * @return \Orkestra\Transactor\Entity\Credentials
     */
    public function getCardCredentials()
    {
        return $this->cardCredentials;
    }

    /**
     * @param \Orkestra\Transactor\Entity\Credentials $cashCredentials
     */
    public function setCashCredentials(Credentials $cashCredentials)
    {
        $this->cashCredentials = $cashCredentials;
    }

    /**
     * @return \Orkestra\Transactor\Entity\Credentials
     */
    public function getCashCredentials()
    {
        return $this->cashCredentials;
    }

    /**
     * @param \Orkestra\Transactor\Entity\Credentials $checkCredentials
     */
    public function setCheckCredentials(Credentials $checkCredentials)
    {
        $this->checkCredentials = $checkCredentials;
    }

    /**
     * @return \Orkestra\Transactor\Entity\Credentials
     */
    public function getCheckCredentials()
    {
        return $this->checkCredentials;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     */
    public function setOffice($office)
    {
        $this->office = $office;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @param string $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }
}
