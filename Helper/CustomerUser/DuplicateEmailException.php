<?php

namespace TerraMar\Bundle\SalesBundle\Helper\CustomerUser;

/**
 * Thrown when creating or updating a CustomerUser with a new email that already exists
 */
class DuplicateEmailException extends \RuntimeException
{
}
