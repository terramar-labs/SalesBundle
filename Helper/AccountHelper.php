<?php

namespace TerraMar\Bundle\SalesBundle\Helper;

use Orkestra\Transactor\TransactorFactory;
use Orkestra\Transactor\Entity\Transaction\TransactionType;
use Orkestra\Transactor\Entity\Transaction\NetworkType;
use TerraMar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository;
use TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use Orkestra\Transactor\Entity\Transaction;
use Orkestra\Transactor\Entity\Account\PointsAccount;

class AccountHelper
{
    /**
     * @var \Orkestra\Transactor\TransactorFactory
     */
    protected $factory;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository
     */
    protected $repository;

    /**
     * Constructor
     *
     * @param \Orkestra\Transactor\TransactorFactory $factory
     * @param \TerraMar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository $repository
     */
    public function __construct(TransactorFactory $factory, OfficeConfigurationRepository $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    public function addCredit(CustomerSalesProfile $profile, $amount)
    {
        if (!$profile->getPointsAccount()) {
            throw new \RuntimeException('The given CustomerSalesProfile has no associated PointsAccount');
        }

        if ($amount <= 0) {
            throw new \RuntimeException('Amount must be greater than 0');
        }

        $configuration = $this->repository->getConfiguration($profile->getOffice());

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
        $transaction->setType(new TransactionType(TransactionType::CREDIT));
        $transaction->setAccount($profile->getPointsAccount());
        $transaction->setCredentials($credentials);

        return $transactor->transact($transaction);
    }
}
