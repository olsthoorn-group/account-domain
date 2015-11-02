<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\ActivationCode;

/**
 * Thrown when an activation timed out.
 */
class ActivationTimedOut extends ActivationOfAccountFailed
{
    public static function withActivationCode(ActivationCode $code)
    {
        $message = sprintf('ActivationCode `%s` timed out', $code->toString());

        return new self($message);
    }
}
