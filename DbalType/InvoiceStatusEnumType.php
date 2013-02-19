<?php

namespace TerraMar\Bundle\SalesBundle\DbalType;

use Orkestra\Common\DbalType\AbstractEnumType;

/**
 * Invoice Status EnumType
 *
 * Provides integration for the Invoice Status enumeration and Doctrine DBAL
 */
class InvoiceStatusEnumType extends AbstractEnumType
{
    /**
     * @var string The unique name for this EnumType
     */
    protected $name = 'enum.terramar.sales.invoice_status';

    /**
     * @var string The fully qualified class name of the Enum that this class wraps
     */
    protected $class = 'TerraMar\Bundle\SalesBundle\Entity\Invoice\InvoiceStatus';
}
