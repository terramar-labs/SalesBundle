<?php

namespace Terramar\Bundle\SalesBundle\Entity\Contract;

use Orkestra\Common\Type\Enum;

class BillingFrequency extends Enum
{
    const WEEKLY = 'Weekly';

    const BI_WEEKLY = 'Bi-weekly';

    const MONTHLY = 'Monthly';

    const BI_MONTHLY = 'Bi-monthly';

    const QUARTERLY = 'Quarterly';

    const SEMI_ANNUALLY = 'Semi-annually';

    const ANNUALLY = 'Annually';
}
