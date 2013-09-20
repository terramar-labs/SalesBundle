<?php

namespace Terramar\Bundle\SalesBundle\DbalType;

use Orkestra\Common\DbalType\AbstractEnumType;

/**
 * Alert Type EnumType
 *
 * Provides integration for the Alert Status enumeration and Doctrine DBAL
 */
class AlertTypeEnumType extends AbstractEnumType
{
    /**
     * @var string The unique name for this EnumType
     */
    protected $name = 'enum.terramar.sales.alert_type';

    /**
     * @var string The fully qualified class name of the Enum that this class wraps
     */
    protected $class = 'Terramar\Bundle\SalesBundle\Model\Alert\AlertType';
}
