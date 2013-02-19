<?php

namespace TerraMar\Bundle\SalesBundle\DbalType;

use Orkestra\Common\DbalType\AbstractEnumType;

/**
 * Alert Status EnumType
 *
 * Provides integration for the Alert Status enumeration and Doctrine DBAL
 */
class AlertStatusEnumType extends AbstractEnumType
{
    /**
     * @var string The unique name for this EnumType
     */
    protected $name = 'enum.terramar.sales.alert_status';

    /**
     * @var string The fully qualified class name of the Enum that this class wraps
     */
    protected $class = 'TerraMar\Bundle\SalesBundle\Entity\Alert\AlertStatus';
}
