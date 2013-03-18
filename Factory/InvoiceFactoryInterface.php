<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\SalesBundle\Entity\Contract;
use Terramar\Bundle\SalesBundle\Entity\Invoice;
use DateTime;
use Terramar\Bundle\SalesBundle\Entity\Invoice\InvoiceType;

interface InvoiceFactoryInterface
{
    /**
     * Creates a set of invoices based on a Contract's Agreement
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Contract $contract
     *
     * @return array
     */
    function createInvoicesForContract(Contract $contract);

    /**
     * Creates a new invoice
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Contract $contract
     * @param \DateTime $dateDue
     * @param \Terramar\Bundle\SalesBundle\Entity\Invoice\InvoiceType $type
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Invoice
     */
    function createInvoice(Contract $contract, DateTime $dateDue, InvoiceType $type = null);

    /**
     * Builds a new invoice
     *
     * This method should do any additional work related to invoiced creation.
     * It is called at time of Invoice creation.
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Invoice $invoice
     *
     * @return \Terramar\Bundle\SalesBundle\Entity\Invoice
     */
    function buildInvoice(Invoice $invoice);
}
