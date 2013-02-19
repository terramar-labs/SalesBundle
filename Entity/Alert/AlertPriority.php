<?php

namespace Terramar\Bundle\SalesBundle\Entity\Alert;

use Orkestra\Common\Type\Enum;

class AlertPriority extends Enum
{
    const LOW = 'Low';

    const NORMAL = 'Normal';

    const HIGH = 'High';

}
