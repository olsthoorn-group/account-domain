<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Core\Domain\DomainException;

/**
 * Thrown when something went wrong with the activation of an account.
 */
class ActivationOfAccountFailed extends DomainException
{
}
