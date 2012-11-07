<?php

namespace TerraMar\Bundle\SalesBundle\DbalType;

use Orkestra\Common\DBAL\Types\EnumTypeBase;

/**
 * Contract Status EnumType
 *
 * Provides integration for the Contract Status enumeration and Doctrine DBAL
 */
class ContractStatusEnumType extends EnumTypeBase
{
    /**
     * @var string The unique name for this EnumType
     */
    protected $_name = 'enum.terramar.sales.contract_status';

    /**
     * @var string The fully qualified class name of the Enum that this class wraps
     */
    protected $_class = 'TerraMar\Bundle\SalesBundle\Entity\Contract\ContractStatus';
}
