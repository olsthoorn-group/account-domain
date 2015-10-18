<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Core\Domain\DomainException;

/**
 * Thrown when something went wrong with the creation of a new account.
 */
class CreationOfAccountFailed extends DomainException
{
}
