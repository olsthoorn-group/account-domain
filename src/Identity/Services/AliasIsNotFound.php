<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\Email;
use OG\Core\Domain\DomainException;

/**
 * Thrown when an alias is not found.
 */
class AliasIsNotFound extends DomainException
{
    public static function withAlias(Email $alias)
    {
        $message = sprintf('Alias `%s` could not be found', $alias->toString());

        return new self($message);
    }
}
