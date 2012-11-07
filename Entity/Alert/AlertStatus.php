<?php

namespace TerraMar\Bundle\SalesBundle\Entity\Alert;

use Orkestra\Common\Type\Enum;

class AlertStatus extends Enum
{
    const POSTED = 'Posted';

    // confirms the 'assigned to' person has seen the alert
    const VIEWED = 'Viewed';

    const IN_PROGRESS = 'In Progress';

    const COMPLETED = 'Completed';

}
