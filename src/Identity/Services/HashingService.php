<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\HashedPassword;
use OG\Account\Domain\Identity\Model\Password;

/**
 * Hashes a password.
 */
interface HashingService
{
    /**
     * Create a new hashed password.
     *
     * @param Password $password
     *
     * @return HashedPassword
     */
    public function hash(Password $password);
}
