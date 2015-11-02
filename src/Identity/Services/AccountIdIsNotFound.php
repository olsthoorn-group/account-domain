<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\AccountId;
use OG\Core\Domain\DomainException;

/**
 * Thrown when an account id is not found.
 */
class AccountIdIsNotFound extends DomainException
{
    public static function withId(AccountId $id)
    {
        $message = sprintf('Account id `%s` is not found', $id->toString());

        return new self($message);
    }
}
