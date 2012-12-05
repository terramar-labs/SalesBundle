<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile;
use TerraMar\Bundle\SalesBundle\Factory\CustomerUserFactoryInterface;
use Orkestra\Transactor\Entity\Account\SimpleAccount;
use Orkestra\Transactor\Entity\Account\PointsAccount;
use TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use TerraMar\Bundle\SalesBundle\Entity\Office;

class OfficeSalesProfileFactory implements OfficeSalesProfileFactoryInterface
{
    /**
     * @var \TerraMar\Bundle\SalesBundle\Factory\PaymentAccountFactoryInterface
     */
    protected $paymentAccountFactory;

    /**
     * Constructor
     *
     * @param \TerraMar\Bundle\SalesBundle\Factory\PaymentAccountFactoryInterface $paymentAccountFactory
     */
    public function __construct(PaymentAccountFactoryInterface $paymentAccountFactory)
    {
        $this->paymentAccountFactory = $paymentAccountFactory;
    }

    /**
     * Creates a new OfficeSalesProfile from the given Office
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile
     */
    public function create(Office $office)
    {
        $profile = new OfficeSalesProfile();
        $profile->setOffice($office);

        $pointsAccount = new PointsAccount();
        $pointsAccount->setName('Points');
        $this->paymentAccountFactory->buildAccountFromOffice($pointsAccount, $office);

        $defaultAccount = new SimpleAccount();
        $defaultAccount->setAlias('Cash or check');
        $this->paymentAccountFactory->buildAccountFromOffice($defaultAccount, $office);

        $profile->addAccount($pointsAccount);
        $profile->addAccount($defaultAccount);

        return $profile;
    }
}
