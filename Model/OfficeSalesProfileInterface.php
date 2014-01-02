<?php

namespace Terramar\Bundle\SalesBundle\Model;

use Orkestra\Bundle\ApplicationBundle\Model\UserInterface;
use Terramar\Bundle\CustomerBundle\Model\CustomerInterface;
use Terramar\Bundle\SalesBundle\Entity\Office;

interface OfficeSalesProfileInterface extends SalesProfileInterface
{
    /**
     * @return Office
     */
    public function getOffice();

    /**
     * @param Office $office
     */
    public function setOffice(Office $office);
}
