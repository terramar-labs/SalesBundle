<?php

namespace Terramar\Bundle\SalesBundle\Entity\Invoice;

use Orkestra\Common\Type\Enum;

class InvoiceType extends Enum
{
    const ONE_TIME = 'One time';

    const RECURRING = 'Recurring';

    const SETUP = 'Setup';
}
