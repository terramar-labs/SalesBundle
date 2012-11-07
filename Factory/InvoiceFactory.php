<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\Contract;
use TerraMar\Bundle\SalesBundle\Entity\Invoice\InvoiceStatus;
use TerraMar\Bundle\SalesBundle\Entity\Invoice;
use DateTime;

class InvoiceFactory implements InvoiceFactoryInterface
{
    /**
     * Creates a set of invoices based on a Contract's Agreement
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Contract $contract
     *
     * @throws \RuntimeException
     */
    public function createInvoicesForContract(Contract $contract)
    {
        throw new \RuntimeException('This method is not implemented.');
    }

    /**
     * Creates a new invoice
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Contract $contract
     * @param \DateTime $dateDue
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Invoice
     */
    public function createInvoice(Contract $contract, DateTime $dateDue)
    {
        $invoice = new Invoice();
        $invoice->setContract($contract);
        $invoice->setDateDue($dateDue);
        $invoice->setStatus(new InvoiceStatus(InvoiceStatus::NOT_SENT));

        return $invoice;
    }
}
