<?php

namespace TerraMar\Bundle\SalesBundle\DbalType;

use Orkestra\Common\DBAL\Types\EnumTypeBase;

/**
 * Found By Type EnumType
 *
 * Provides integration for the Found By Type enumeration and Doctrine DBAL
 */
class FoundByTypeEnumType extends EnumTypeBase
{
    /**
     * @var string The unique name for this EnumType
     */
    protected $_name = 'enum.terramar.sales.found_by_type';

    /**
     * @var string The fully qualified class name of the Enum that this class wraps
     */
    protected $_class = 'TerraMar\Bundle\SalesBundle\Entity\Contract\FoundByType';
}
