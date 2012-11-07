<?php

namespace TerraMar\Bundle\SalesBundle\Entity\Contract;

use Orkestra\Common\Type\Enum;

class ContractStatus extends Enum
{
    const ACTIVE = 'Active';

    const COMPLETE = 'Complete';

    const BROKEN = 'Broken';
}
