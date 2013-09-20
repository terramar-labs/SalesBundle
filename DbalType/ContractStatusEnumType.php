<?php

namespace Terramar\Bundle\SalesBundle\DbalType;

use Orkestra\Common\DbalType\AbstractEnumType;

/**
 * Contract Status EnumType
 *
 * Provides integration for the Contract Status enumeration and Doctrine DBAL
 */
class ContractStatusEnumType extends AbstractEnumType
{
    /**
     * @var string The unique name for this EnumType
     */
    protected $name = 'enum.terramar.sales.contract_status';

    /**
     * @var string The fully qualified class name of the Enum that this class wraps
     */
    protected $class = 'Terramar\Bundle\SalesBundle\Model\Contract\ContractStatus';
}
