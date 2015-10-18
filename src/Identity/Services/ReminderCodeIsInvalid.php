<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\ReminderCode;
use OG\Core\Domain\DomainException;

/**
 * Thrown when a ReminderCode is invalid.
 */
class ReminderCodeIsInvalid extends DomainException
{
    public static function withReminderCode(ReminderCode $code)
    {
        $message = sprintf('ReminderCode `%s` is invalid', $code->toString());

        return new self($message);
    }
}
