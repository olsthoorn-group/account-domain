<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\Email;

/**
 * Thrown when an alias is already used by another account.
 */
class AliasIsAlreadyInUse extends CreationOfAccountFailed
{
    public static function withAlias(Email $alias)
    {
        $message = sprintf('Alias `%s` is already in use', $alias->toString());

        return new self($message);
    }
}
