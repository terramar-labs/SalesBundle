<?php

namespace Terramar\Bundle\SalesBundle\Model\Office;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Transactor\Entity\Credentials;
use Terramar\Bundle\SalesBundle\Entity\Office;
use Terramar\Bundle\SalesBundle\Model\PersistentModelInterface;

/**
 * An office's application settings
 */
interface OfficeConfigurationInterface extends PersistentModelInterface
{
    /**
     * @param boolean $pointsEnabled
     */
    public function setPointsEnabled($pointsEnabled);

    /**
     * @return boolean
     */
    public function getPointsEnabled();

    /**
     * @return boolean
     */
    public function isPointsEnabled();

    /**
     * @param \Orkestra\Transactor\Entity\Credentials $pointsCredentials
     */
    public function setPointsCredentials(Credentials $pointsCredentials);

    /**
     * @return \Orkestra\Transactor\Entity\Credentials
     */
    public function getPointsCredentials();

    /**
     * @param \Orkestra\Transactor\Entity\Credentials $achCredentials
     */
    public function setAchCredentials(Credentials $achCredentials);

    /**
     * @return \Orkestra\Transactor\Entity\Credentials
     */
    public function getAchCredentials();

    /**
     * @param \Orkestra\Transactor\Entity\Credentials $cardCredentials
     */
    public function setCardCredentials(Credentials $cardCredentials);

    /**
     * @return \Orkestra\Transactor\Entity\Credentials
     */
    public function getCardCredentials();

    /**
     * @param \Orkestra\Transactor\Entity\Credentials $cashCredentials
     */
    public function setCashCredentials(Credentials $cashCredentials);

    /**
     * @return \Orkestra\Transactor\Entity\Credentials
     */
    public function getCashCredentials();

    /**
     * @param \Orkestra\Transactor\Entity\Credentials $checkCredentials
     */
    public function setCheckCredentials(Credentials $checkCredentials);

    /**
     * @return \Orkestra\Transactor\Entity\Credentials
     */
    public function getCheckCredentials();

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     */
    public function setOffice(Office $office);

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Office
     */
    public function getOffice();

    /**
     * @param string $timezone
     */
    public function setTimezone($timezone);

    /**
     * @return string
     */
    public function getTimezone();
}
