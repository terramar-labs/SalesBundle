<?php

namespace Terramar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Transactor\Entity\Transaction\TransactionType;
use Terramar\Bundle\SalesBundle\Entity\Invoice\InvoiceTransaction;
use Orkestra\Transactor\Entity\Result\ResultStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Orkestra\Transactor\Entity\Transaction;
use Terramar\Bundle\SalesBundle\Entity\Invoice\InvoiceItem;
use Orkestra\Common\Entity\AbstractEntity;
use Terramar\Bundle\SalesBundle\Model\Invoice\InvoiceStatus;
use Terramar\Bundle\SalesBundle\Model\Invoice\InvoiceType;

/**
 * An invoice
 *
 * @ORM\Entity
 * @ORM\Table(name="terramar_invoices")
 */
class Invoice extends AbstractEntity
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_due", type="date")
     */
    protected $dateDue;

    /**
     * @var float
     *
     * @ORM\Column(name="amount_due", type="decimal", precision=8, scale=2)
     */
    protected $amountDue;

    /**
     * @var \Terramar\Bundle\SalesBundle\Model\Invoice\InvoiceStatus
     *
     * @ORM\Column(name="status", type="enum.terramar.sales.invoice_status")
     */
    protected $status;

    /**
     * @var \Terramar\Bundle\SalesBundle\Model\Invoice\InvoiceType
     *
     * @ORM\Column(name="type", type="enum.terramar.sales.invoice_type")
     */
    protected $type;

    /**
     * @var float
     *
     * @ORM\Column(name="balance", type="decimal", precision=8, scale=2)
     */
    protected $balance;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Orkestra\Transactor\Entity\Transaction", cascade={"persist"})
     * @ORM\JoinTable(name="terramar_invoice_transactions",
     *      joinColumns={@ORM\JoinColumn(name="invoice_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="transaction_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $transactions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Terramar\Bundle\SalesBundle\Entity\Invoice\InvoiceItem", mappedBy="invoice", cascade={"persist"})
     */
    protected $items;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Terramar\Bundle\SalesBundle\Entity\Invoice\InvoiceTransaction", mappedBy="invoice", cascade={"persist"})
     */
    protected $invoiceTransactions;

    /**
     * @var \Terramar\Bundle\SalesBundle\Model\ContractInterface
     *
     * @ORM\ManyToOne(targetEntity="Terramar\Bundle\SalesBundle\Model\ContractInterface", inversedBy="invoices")
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     */
    protected $contract;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->invoiceTransactions = new ArrayCollection();
        $this->type = new InvoiceType(InvoiceType::ONE_TIME);
    }

    /**
     * Returns true if the invoice has been paid
     *
     * @return bool
     */
    public function isPaid()
    {
        return InvoiceStatus::PAID == $this->status;
    }

    /**
     * Returns true if the invoice is past due
     *
     * @return bool
     */
    public function isPastDue()
    {
        return InvoiceStatus::PAST_DUE == $this->status;
    }

    /**
     * Returns true if the invoice is due
     *
     * @return bool
     */
    public function isDue()
    {
        return in_array($this->status, array(InvoiceStatus::DUE, InvoiceStatus::PAST_DUE));
    }

    /**
     * @param float $amountDue
     */
    public function setAmountDue($amountDue)
    {
        $difference = $amountDue - $this->amountDue;

        if (abs($difference - 0.00) > 0.001) {
            $this->addItem('Price adjustment', $difference);
        }
    }

    /**
     * @return float
     */
    public function getAmountDue()
    {
        return $this->amountDue;
    }

    /**
     * @param float $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Contract $contract
     */
    public function setContract($contract)
    {
        $this->contract = $contract;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Contract
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param \DateTime $dateDue
     */
    public function setDateDue($dateDue)
    {
        $this->dateDue = $dateDue;
    }

    /**
     * @return \DateTime
     */
    public function getDateDue()
    {
        return $this->dateDue;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Model\Invoice\InvoiceStatus $status
     */
    public function setStatus(InvoiceStatus $status)
    {
        $this->status = $status;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Model\Invoice\InvoiceStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Model\Invoice\InvoiceType $type
     */
    public function setType(InvoiceType $type)
    {
        $this->type = $type;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Model\Invoice\InvoiceType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $transactions
     */
    public function setTransactions($transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * @param \Orkestra\Transactor\Entity\Transaction $transaction
     */
    protected function addTransaction(Transaction $transaction)
    {
        $this->transactions->add($transaction);
        if (
            TransactionType::REFUND != $transaction->getType()
            && in_array($transaction->getStatus(), array(
                ResultStatus::APPROVED,
                ResultStatus::PROCESSED,
                ResultStatus::PENDING
            ))
        ) {
            $this->balance -= $transaction->getAmount();
        }
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $invoiceTransactions
     */
    public function addInvoiceTransaction(InvoiceTransaction $invoiceTransaction)
    {
        $this->invoiceTransactions->add($invoiceTransaction);
        $this->addTransaction($invoiceTransaction->getTransaction());
        $invoiceTransaction->setInvoice($this);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoiceTransactions()
    {
        return $this->invoiceTransactions;
    }

    /**
     * @param string $description
     * @param float $price
     *
     * @return InvoiceItem
     */
    public function addItem($description, $price)
    {
        $item = new InvoiceItem();
        $item->setDescription($description);
        $item->setPrice($price);
        $item->setInvoice($this);

        $this->items->add($item);
        $this->amountDue += $price;
        $this->balance += $price;

        return $item;
    }
}
