<?php

namespace TerraMar\Bundle\SalesBundle\Model;

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
}
