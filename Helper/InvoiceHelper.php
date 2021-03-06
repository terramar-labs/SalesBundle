<?php

namespace Terramar\Bundle\SalesBundle\Helper;

use Terramar\Bundle\SalesBundle\Entity\Invoice;
use Terramar\Bundle\SalesBundle\Entity\Office;
use Orkestra\Transactor\Entity\Transaction\TransactionType;
use Orkestra\Transactor\Entity\Transaction;
use Orkestra\Bundle\ApplicationBundle\Entity\User;
use Terramar\Bundle\SalesBundle\Entity\Invoice\InvoiceTransaction;
use Terramar\Bundle\SalesBundle\Entity\Invoice\InvoiceStatus;
use Terramar\Bundle\SalesBundle\Model\Invoice\SerializedPayment;
use Doctrine\Common\Persistence\ObjectRepository;
use Orkestra\Transactor\TransactorFactory;
use Orkestra\Transactor\Entity\Transaction\NetworkType;
use Terramar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository;
use Terramar\Bundle\SalesBundle\Model\Invoice\Payment;

class InvoiceHelper
{
    /**
     * @var \Terramar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository
     */
    protected $configurationRepository;

    /**
     * @var \Orkestra\Transactor\TransactorFactory
     */
    protected $transactorFactory;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $accountRepository;

    /**
     * Constructor
     *
     * @param \Terramar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository $configurationRepository
     * @param \Orkestra\Transactor\TransactorFactory $transactorFactory
     * @param \Doctrine\Common\Persistence\ObjectRepository $accountRepository
     */
    public function __construct(
        OfficeConfigurationRepository $configurationRepository,
        TransactorFactory $transactorFactory,
        ObjectRepository $accountRepository
    ) {
        $this->configurationRepository = $configurationRepository;
        $this->transactorFactory = $transactorFactory;
        $this->accountRepository = $accountRepository;
    }

    /**
     * Processes a refund
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Invoice $invoice
     * @param \Orkestra\Transactor\Entity\Transaction $transaction
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\User $user
     *
     * @return \Orkestra\Transactor\Entity\Transaction
     * @throws \RuntimeException
     */
    public function processRefund(Invoice $invoice, Transaction $transaction, User $user)
    {
        if ($transaction->isRefunded()) {
            throw new \RuntimeException('The transaction has already been refunded');
        }

        $refund = $transaction->createChild(new TransactionType(TransactionType::REFUND));

        $transactor = $this->transactorFactory->getTransactor($refund->getCredentials()->getTransactor());
        $transactor->transact($refund);

        $invoiceTransaction = new InvoiceTransaction();
        $invoiceTransaction->setTransaction($refund);
        $invoiceTransaction->setUser($user);

        $invoice->addInvoiceTransaction($invoiceTransaction);

        return $refund;
    }

    /**
     * Processes a payment
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     * @param \Terramar\Bundle\SalesBundle\Entity\Invoice $invoice
     * @param \Terramar\Bundle\SalesBundle\Model\Invoice\Payment $payment
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\User $user
     *
     * @throws \RuntimeException
     * @return \Orkestra\Transactor\Entity\Transaction
     */
    public function processPayment(Office $office, Invoice $invoice, Payment $payment, User $user = null)
    {
        $configuration = $this->configurationRepository->findOneByOffice($office);
        if (!$configuration) {
            throw new \RuntimeException('Unable to locate office configuration');
        }

        $transaction = $payment->getTransaction();

        switch ($transaction->getNetwork()->getValue()) {
            case NetworkType::CARD:
                $credentials = $configuration->getCardCredentials();

                break;
            case NetworkType::ACH:
                $credentials = $configuration->getAchCredentials();

                break;
            case NetworkType::CASH:
                $credentials = $configuration->getCashCredentials();

                break;
            case NetworkType::CHECK:
                $credentials = $configuration->getCheckCredentials();

                break;
            case NetworkType::POINTS:
                $credentials = $configuration->getPointsCredentials();

                break;
            default:
                throw new \RuntimeException(sprintf('Invalid network type "%s"', $transaction->getNetwork()->getValue()));
        }

        if (empty($credentials)) {
            throw new \RuntimeException(sprintf('No suitable payment transactor configured for processing %s payments.', $transaction->getNetwork()->getValue()));
        }

        $transaction->setCredentials($credentials);

        $transactor = $this->transactorFactory->getTransactor($credentials->getTransactor());
        $transactor->transact($transaction);

        $invoiceTransaction = new InvoiceTransaction();
        $invoiceTransaction->setTransaction($transaction);
        if ($user) {
            $invoiceTransaction->setUser($user);
        }

        if ($invoice->getStatus() == InvoiceStatus::PAST_DUE) {
            $invoiceTransaction->setPastDue(true);
        }

        $invoice->addInvoiceTransaction($invoiceTransaction);

        if ($invoice->getBalance() <= 0) {
            $invoice->setStatus(new InvoiceStatus(InvoiceStatus::PAID));
        }

        return $transaction;
    }

    /**
     * Serializes a Payment object for storage
     *
     * @param \Terramar\Bundle\SalesBundle\Model\Invoice\Payment $payment
     *
     * @return \Terramar\Bundle\SalesBundle\Model\Invoice\SerializedPayment
     */
    public function serializePayment(Payment $payment)
    {
        $serializedPayment = new SerializedPayment();
        $serializedPayment->amount = $payment->getAmount();
        $serializedPayment->method = $payment->getMethod() ? $payment->getMethod()->getValue() : null;
        $serializedPayment->account = $payment->getAccount();

        return $serializedPayment;
    }

    /**
     * Hydrates a serialized payment
     *
     * @param \Terramar\Bundle\SalesBundle\Model\Invoice\SerializedPayment $serializedPayment
     *
     * @return \Terramar\Bundle\SalesBundle\Model\Invoice\Payment
     */
    public function hydrateSerializedPayment(SerializedPayment $serializedPayment)
    {
        $payment = new Payment();
        if ($serializedPayment->account) {
            $account = $this->accountRepository->find($serializedPayment->account);
            $payment->setAccount($account);
        }

        if ($serializedPayment->method) {
            $payment->setMethod(new NetworkType($serializedPayment->method));
        }

        $payment->setAmount($serializedPayment->amount);

        return $payment;
    }
}
