<?php

namespace TerraMar\Bundle\SalesBundle\DbalType;

use Orkestra\Common\DBAL\Types\EnumTypeBase;

/**
 * Invoice Type EnumType
 *
 * Provides integration for the Invoice Type enumeration and Doctrine DBAL
 */
class InvoiceTypeEnumType extends EnumTypeBase
{
    /**
     * @var string The unique name for this EnumType
     */
    protected $_name = 'enum.terramar.sales.invoice_type';

    /**
     * @var string The fully qualified class name of the Enum that this class wraps
     */
    protected $_class = 'TerraMar\Bundle\SalesBundle\Entity\Invoice\InvoiceType';
}
