<?php

namespace TerraMar\Bundle\SalesBundle\Entity\Invoice;

use Orkestra\Common\Type\Enum;

class InvoiceStatus extends Enum
{
    const PAID = 'Paid';

    const NOT_SENT = 'Not sent';

    const SENT = 'Sent';

    const DUE = 'Due';

    const PAST_DUE = 'Past due';

    const COLLECTIONS = 'Collections';

    const CANCELLED = 'Cancelled';
}
