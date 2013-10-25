<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Orkestra\Bundle\ApplicationBundle\Model\UserInterface;
use Terramar\Bundle\SalesBundle\Entity\Office;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;

interface OfficeUserFactoryInterface
{
    /**
     * Creates a new user assigned to the given office
     *
     * @param Office        $office
     * @param UserInterface $user
     * @param array         $roles
     *
     * @return OfficeUser
     */
    public function createOfficeUser(Office $office, UserInterface $user, array $roles = array());
}
