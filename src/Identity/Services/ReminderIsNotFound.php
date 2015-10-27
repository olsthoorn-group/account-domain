<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\Email;

/**
 * Thrown when a reminder could not be found.
 */
class ReminderIsNotFound extends ReminderOfAccountFailed
{
    public static function withAlias(Email $alias)
    {
        $message = sprintf('Reminder for the alias `%s` could not be found', $alias->toString());

        return new self($message);
    }
}
