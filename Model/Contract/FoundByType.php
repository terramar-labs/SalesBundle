<?php

namespace Terramar\Bundle\SalesBundle\Model\Contract;

use Orkestra\Common\Type\Enum;

class FoundByType extends Enum
{
    const OTHER = 'Other';

    const DOOR_TO_DOOR = 'Door-to-door';

    const CALL_IN = 'Call-in';

    const FLYER = 'Flyer';

    const YELLOW_PAGES = 'Yellow pages';

    const WEBSITE = 'Website';
}
