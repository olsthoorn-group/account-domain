<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\ActivationCode;

/**
 * Thrown when a ActivationCode is invalid.
 */
class ActivationCodeIsInvalid extends ActivationOfAccountFailed
{
    public static function withActivationCode(ActivationCode $code)
    {
        $message = sprintf('ReminderCode `%s` is invalid', $code->toString());

        return new self($message);
    }
}
