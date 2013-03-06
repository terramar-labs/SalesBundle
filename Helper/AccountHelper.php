<?php

namespace Terramar\Bundle\SalesBundle\Helper;

use Orkestra\Transactor\TransactorFactory;
use Orkestra\Transactor\Entity\Transaction\TransactionType;
use Orkestra\Transactor\Entity\Transaction\NetworkType;
use Terramar\Bundle\SalesBundle\Model\SalesProfileInterface;
use Terramar\Bundle\SalesBundle\Repository\OfficeConfigurationRepository;
use Orkestra\Transactor\Entity\Transaction;

class AccountHelper implements AccountHelperInterface
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
        $this->factory    = $factory;
        $this->repository = $repository;
    }

    /**
     * Attempts to add credit to a Sales Profile's configured PointsAccount
     *
     * @param \Terramar\Bundle\SalesBundle\Model\SalesProfileInterface $profile
     * @param integer                                                  $amount
     *
     * @return \Orkestra\Transactor\Entity\Result
     *
     * @throws \RuntimeException
     */
    public function addCredit(SalesProfileInterface $profile, $amount)
    {
        if (!$profile->getPointsAccount()) {
            throw new \RuntimeException('The given CustomerSalesProfile has no associated PointsAccount');
        }

        if ($amount <= 0) {
            throw new \RuntimeException('Amount must be greater than 0');
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
        $transaction->setType(new TransactionType(TransactionType::CREDIT));
        $transaction->setAccount($profile->getPointsAccount());
        $transaction->setCredentials($credentials);

        return $transactor->transact($transaction);
    }
}
