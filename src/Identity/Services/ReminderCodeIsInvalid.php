<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\ReminderCode;

/**
 * Thrown when a ReminderCode is invalid.
 */
class ReminderCodeIsInvalid extends ReminderOfAccountFailed
{
    public static function withReminderCode(ReminderCode $code)
    {
        $message = sprintf('ReminderCode `%s` is invalid', $code->toString());

        return new self($message);
    }
}
