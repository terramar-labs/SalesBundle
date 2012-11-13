<?php

namespace TerraMar\Bundle\SalesBundle\Entity\Alert;

use Orkestra\Common\Type\Enum;

class AlertType extends Enum
{
    const ALERT = 'Alert';

    const TODO = 'To do';
}
