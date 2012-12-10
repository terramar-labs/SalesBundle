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

        return $this->buildProfile($profile);
    }

    /**
     * Builds the given OfficeSalesProfile
     *
     * This method is called when a new OfficeSalesProfile is created.
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile $profile
     *
     * @throws \RuntimeException
     * @return \TerraMar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile
     */
    public function buildProfile(OfficeSalesProfile $profile)
    {
        $office = $profile->getOffice();
        if (!$office) {
            throw new \RuntimeException('The given OfficeSalesProfile must be associated with an Office');
        }

        $pointsAccount = new PointsAccount();
        $pointsAccount->setAlias('Points');
        $profile->setPointsAccount($pointsAccount);

        $defaultAccount = new SimpleAccount();
        $defaultAccount->setAlias('Cash or check');
        $profile->addAccount($defaultAccount);

        foreach ($profile->getAccounts() as $account) {
            $this->paymentAccountFactory->buildAccountFromOffice($account, $office);
        }

        return $profile;
    }
}
