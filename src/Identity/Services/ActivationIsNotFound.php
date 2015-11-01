<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\Email;

/**
 * Thrown when an activation could not be found.
 */
class ActivationIsNotFound extends ActivationOfAccountFailed
{
    public static function withAlias(Email $alias)
    {
        $message = sprintf('Activation for the alias `%s` could not be found', $alias->toString());

        return new self($message);
    }
}
