<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\Contract;
use DateTime;

interface InvoiceFactoryInterface
{
    /**
     * Creates a set of invoices based on a Contract's Agreement
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Contract $contract
     *
     * @return array
     */
    function createInvoicesForContract(Contract $contract);

    /**
     * Creates a new invoice
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Contract $contract
     * @param \DateTime $dateDue
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Invoice
     */
    function createInvoice(Contract $contract, DateTime $dateDue);
}
