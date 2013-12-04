<?php

namespace Terramar\Bundle\SalesBundle\Helper;

use Orkestra\Transactor\TransactorFactory;
use Orkestra\Transactor\Entity\Transaction\TransactionType;
use Orkestra\Transactor\Entity\Transaction\NetworkType;
use Terramar\Bundle\SalesBundle\Model\SalesProfileInterface;
use Terramar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository;
use Terramar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use Orkestra\Transactor\Entity\Transaction;
use Orkestra\Transactor\Entity\Account\PointsAccount;

class AccountHelper
{
    /**
     * @var \Orkestra\Transactor\TransactorFactory
     */
    protected $factory;

    /**
     * @var \Terramar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository
     */
    protected $repository;

    /**
     * Constructor
     *
     * @param \Orkestra\Transactor\TransactorFactory $factory
     * @param \Terramar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository $repository
     */
    public function __construct(TransactorFactory $factory, OfficeConfigurationRepository $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    public function affectCredit(SalesProfileInterface $profile, $amount)
    {
        if($amount == 0) {
            throw new \RuntimeException('Amount must be non zero');
        }

        if($amount > 0){
            return $this->addCredit($profile,$amount);
        }
        else
            return $this->removeCredit($profile,$amount);
    }

    public function removeCredit(SalesProfileInterface $profile, $amount)
    {
        if ($amount >= 0) {
            throw new \RuntimeException('Amount must be less than 0');
        }
        $amount *= -1;
        return $this->processCreditTransaction($profile, $amount, TransactionType::SALE);
    }

    public function addCredit(SalesProfileInterface $profile, $amount)
    {
        if ($amount <= 0) {
            throw new \RuntimeException('Amount must be greater than 0');
        }

        return $this->processCreditTransaction($profile, $amount, TransactionType::CREDIT);
    }

    public function processCreditTransaction(SalesProfileInterface $profile, $amount, $transactionType)
    {
        $transactionType = new TransactionType($transactionType);

        if (!$profile->getPointsAccount()) {
            throw new \RuntimeException('The given CustomerSalesProfile has no associated PointsAccount');
        }

        $configuration = $this->repository->findOneByOffice($profile->getOffice());

        if (!$configuration) {
            throw new \RuntimeException('The Office has no associated OfficeConfiguration');
        }

        $credentials = $configuration->getPointsCredentials();

        if (!$credentials) {
            throw new \RuntimeException('The OfficeConfiguration has no associated Points Credentials');
        }

        $transactor = $this->factory->getTransactor($credentials->getTransactor());

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setNetwork(new NetworkType(NetworkType::POINTS));
        $transaction->setType($transactionType);
        $transaction->setAccount($profile->getPointsAccount());
        $transaction->setCredentials($credentials);

        return $transactor->transact($transaction);
    }
}
