<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\Contract;

interface ContractFactoryInterface
{
    /**
     * Builds a new contract
     *
     * This method should do any invoice generation or related. It is called
     * at time of Contract creation.
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Contract $contract
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Contract
     */
    function buildContract(Contract $contract);
}
