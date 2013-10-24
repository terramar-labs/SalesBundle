<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Terramar\Bundle\SalesBundle\Entity\Office;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;

interface OfficeUserFactoryInterface
{
    /**
     * Creates a new user assigned to the given office
     *
     * @param Office $office
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password An optional password, one should be generated if none is passed
     * @param array  $roles    An array of role names to be added as groups
     *
     * @return OfficeUser
     */
    public function createUser(Office $office, $firstName, $lastName, $username, $password = null, array $roles = array());
}
