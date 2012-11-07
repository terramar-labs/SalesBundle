<?php

namespace TerraMar\Bundle\SalesBundle\Model\Invoice;

use Orkestra\Transactor\Entity\Transaction;
use Orkestra\Transactor\Entity\AbstractAccount;
use Serializable;

/**
 * A payment that is ready for storage
 */
class SerializedPayment
{
    /**
     * @var string
     */
    public $method;

    /**
     * @var int
     */
    public $account;

    /**
     * @var float
     */
    public $amount;
}
