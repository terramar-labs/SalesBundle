<?php

namespace Terramar\Bundle\SalesBundle\Model;

interface SalesProfileInterface
{
    /**
     * Gets all of a SalesProfile's Transactor accounts
     *
     * @return \Doctrine\Common\Collections\Collection|\Orkestra\Transactor\Entity\AbstractAccount[]
     */
    function getAccounts();

    /**
     * Gets all of a SalesProfile's Transactor accounts that may be used for Autopay
     *
     * @return \Doctrine\Common\Collections\Collection|\Orkestra\Transactor\Entity\AbstractAccount[]|
     */
    function getAutopayAccounts();

    /**
     * Gets the assign autopay account
     *
     * @return \Orkestra\Transactor\Entity\AbstractAccount
     */
    function getAutopayAccount();

    /**
     * Sets autopay to enabled or disabled
     *
     * @param boolean $autopay
     */
    function setAutopay($autopay);

    /**
     * Returns true if autopay is enabled
     *
     * @return boolean
     */
    function isAutopay();
}
