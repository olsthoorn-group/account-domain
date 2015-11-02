<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\ReminderCode;

/**
 * Thrown when a reminder timed out.
 */
class ReminderTimedOut extends ReminderOfAccountFailed
{
    public static function withReminderCode(ReminderCode $code)
    {
        $message = sprintf('ReminderCode `%s` timed out', $code->toString());

        return new self($message);
    }
}
