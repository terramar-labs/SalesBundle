<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\SalesBundle\Entity\Contract;
use Terramar\Bundle\SalesBundle\Entity\Contract\ContractStatus;

class ContractFactory implements ContractFactoryInterface
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
    public function buildContract(Contract $contract)
    {
        $dateEnd = clone $contract->getDateStart();
        $dateEnd->modify(sprintf('+%s months', $contract->getAgreement()->getLength()));
        $contract->setDateEnd($dateEnd);
        $contract->setStatus(new ContractStatus(ContractStatus::ACTIVE));

        return $contract;
    }
}
