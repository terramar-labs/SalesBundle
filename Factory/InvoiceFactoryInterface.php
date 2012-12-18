<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\Contract;
use TerraMar\Bundle\SalesBundle\Entity\Invoice;
use DateTime;
use TerraMar\Bundle\SalesBundle\Entity\Invoice\InvoiceType;

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
     * @param \TerraMar\Bundle\SalesBundle\Entity\Invoice\InvoiceType $type
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Invoice
     */
    function createInvoice(Contract $contract, DateTime $dateDue, InvoiceType $type = null);

    /**
     * Builds a new invoice
     *
     * This method should do any additional work related to invoiced creation.
     * It is called at time of Invoice creation.
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Invoice $invoice
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Invoice
     */
    function buildInvoice(Invoice $invoice);
}
