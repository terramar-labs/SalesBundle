<?php

namespace TerraMar\Bundle\SalesBundle\Entity\Invoice;

use Doctrine\ORM\Mapping as ORM;
use TerraMar\Bundle\SalesBundle\Entity\Invoice;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * A line item on an invoice
 *
 * @ORM\Entity
 * @ORM\Table(name="terramar_invoice_invoicetransactions")
 */
class InvoiceTransaction extends AbstractEntity
{
    /**
     * @var bool
     *
     * @ORM\Column(name="past_due", type="boolean")
     */
    protected $pastDue = false;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Invoice
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\Invoice", inversedBy="invoiceTransactions")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    protected $invoice;

    /**
     * @var \Orkestra\Transactor\Entity\Transaction
     *
     * @ORM\OneToOne(targetEntity="Orkestra\Transactor\Entity\Transaction", cascade={"persist"})
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    protected $transaction;

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Invoice $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param \Orkestra\Transactor\Entity\Transaction $transaction
     */
    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return \Orkestra\Transactor\Entity\Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param boolean $pastDue
     */
    public function setPastDue($pastDue)
    {
        $this->pastDue = $pastDue;
    }

    /**
     * @return boolean
     */
    public function isPastDue()
    {
        return $this->pastDue;
    }


}
