<?php

namespace TerraMar\Bundle\SalesBundle\Model;

interface SalesProfileInterface
{
    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    function getAccounts();
}
