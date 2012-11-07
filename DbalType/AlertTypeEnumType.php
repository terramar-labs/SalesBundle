<?php

namespace TerraMar\Bundle\SalesBundle\DbalType;

use Orkestra\Common\DBAL\Types\EnumTypeBase;

/**
 * Alert Type EnumType
 *
 * Provides integration for the Alert Status enumeration and Doctrine DBAL
 */
class AlertTypeEnumType extends EnumTypeBase
{
    /**
     * @var string The unique name for this EnumType
     */
    protected $_name = 'enum.terramar.sales.alert_type';

    /**
     * @var string The fully qualified class name of the Enum that this class wraps
     */
    protected $_class = 'TerraMar\Bundle\SalesBundle\Entity\Alert\AlertType';
}
