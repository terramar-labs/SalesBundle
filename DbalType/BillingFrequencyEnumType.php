<?php

namespace Terramar\Bundle\SalesBundle\DbalType;

use Orkestra\Common\DbalType\AbstractEnumType;

/**
 * Billing Frequency EnumType
 *
 * Provides integration for the Found By Type enumeration and Doctrine DBAL
 */
class BillingFrequencyEnumType extends AbstractEnumType
{
    /**
     * @var string The unique name for this EnumType
     */
    protected $name = 'enum.terramar.sales.billing_frequency';

    /**
     * @var string The fully qualified class name of the Enum that this class wraps
     */
    protected $class = 'Terramar\Bundle\SalesBundle\Model\Contract\BillingFrequency';
}
