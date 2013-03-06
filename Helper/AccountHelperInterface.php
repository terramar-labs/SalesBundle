<?php

namespace Terramar\Bundle\SalesBundle\Helper;

use Terramar\Bundle\SalesBundle\Model\SalesProfileInterface;

/**
 * Defines the contract any AccountHelper must follow
 */
interface AccountHelperInterface
{
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
    public function addCredit(SalesProfileInterface $profile, $amount);
}
