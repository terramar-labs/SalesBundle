<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\CustomerBundle\Entity\Customer;
use Terramar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile;
use Terramar\Bundle\SalesBundle\Entity\Office;
use Terramar\Bundle\SalesBundle\Model\OfficeSalesProfileInterface;

/**
 * Defines the contract any OfficeSalesProfileFactory must follow
 */
interface OfficeSalesProfileFactoryInterface
{
    /**
     * Creates a new OfficeSalesProfile from the given Office
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     *
     * @return \Terramar\Bundle\SalesBundle\Model\OfficeSalesProfileInterface
     */
    function create(Office $office);

    /**
     * Builds the given OfficeSalesProfile
     *
     * This method is called when a new OfficeSalesProfile is created.
     *
     * @param \Terramar\Bundle\SalesBundle\Model\OfficeSalesProfileInterface $profile
     *
     * @return \Terramar\Bundle\SalesBundle\Model\OfficeSalesProfileInterface
     */
    function buildProfile(OfficeSalesProfileInterface $profile);
}
