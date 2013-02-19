<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\SalesBundle\Entity\Contract;

interface ContractFactoryInterface
{
    /**
     * Builds a new contract
     *
     * This method should do any invoice generation or related. It is called
     * at time of Contract creation.
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Contract $contract
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Contract
     */
    function buildContract(Contract $contract);
}
