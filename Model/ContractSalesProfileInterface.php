<?php

namespace Terramar\Bundle\SalesBundle\Model;

interface ContractSalesProfileInterface extends PersistentModelInterface
{
    /**
     * Gets all of the profile's contracts
     *
     * @return \Doctrine\Common\Collections\Collection|ContractInterface[]
     */
    public function getContracts();

    /**
     * Adds a contract to this sales profile
     *
     * @param ContractInterface $contract
     */
    public function addContract(ContractInterface $contract);
}
