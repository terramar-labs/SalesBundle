<?php

namespace TerraMar\Bundle\SalesBundle\Model\Invoice;

use Orkestra\Transactor\Entity\Transaction;
use Orkestra\Transactor\Entity\AbstractAccount;
use Serializable;
use Orkestra\Transactor\Entity\Account\BankAccount;

/**
 * Intermediary model for processing payments for an invoice
 */
class Payment
{
    /**
     * @var \Orkestra\Transactor\Entity\Transaction\NetworkType
     */
    protected $method;

    /**
     * @var \Orkestra\Transactor\Entity\AbstractAccount
     */
    protected $account;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var \Orkestra\Transactor\Entity\Transaction
     */
    protected $transaction;

    /**
     * @param \Orkestra\Transactor\Entity\AbstractAccount $account
     */
    public function setAccount(AbstractAccount $account)
    {
        $this->account = $account;
    }

    /**
     * @return \Orkestra\Transactor\Entity\AbstractAccount
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param \Orkestra\Transactor\Entity\Transaction\NetworkType $method
     */
    public function setMethod(Transaction\NetworkType $method)
    {
        $this->method = $method;
    }

    /**
     * @return \Orkestra\Transactor\Entity\Transaction\NetworkType
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets the transaction associated with this payment
     *
     * @return \Orkestra\Transactor\Entity\Transaction
     */
    public function getTransaction()
    {
        if ($this->transaction) {
            return $this->transaction;
        }

        $transaction = new Transaction();
        $transaction->setAccount($this->account);
        $transaction->setAmount($this->amount);
        $transaction->setType(new Transaction\TransactionType(Transaction\TransactionType::SALE));
        $transaction->setNetwork($this->method);

        return $this->transaction = $transaction;
    }
}
