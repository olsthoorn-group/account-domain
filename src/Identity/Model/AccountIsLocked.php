<?php

namespace OG\Account\Domain\Identity\Model;

use OG\Core\Domain\DomainException;

/**
 * Thrown when an account is locked.
 */
class AccountIsLocked extends DomainException
{
    public static function withId(AccountId $id)
    {
        $message = sprintf('Account `%s` is locked', $id->toString());

        return new self($message);
    }
}
