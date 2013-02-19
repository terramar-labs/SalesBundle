<?php

namespace Terramar\Bundle\SalesBundle\Factory\CustomerUser;

/**
 * Thrown when creating or updating a CustomerUser with a new email that already exists
 */
class DuplicateEmailException extends \RuntimeException
{
}
