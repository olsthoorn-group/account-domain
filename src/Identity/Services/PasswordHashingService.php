<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\HashedPassword;
use OG\Account\Domain\Identity\Model\Password;

/**
 * Hashes and verifies a Password.
 */
interface PasswordHashingService
{
    /**
     * Create a new HashedPassword.
     *
     * @param Password $password
     *
     * @return HashedPassword
     */
    public function hash(Password $password);

    /**
     * Checks if the current hash needs to be rehashed.
     *
     * @param HashedPassword $hashedPassword
     *
     * @return bool
     */
    public function needsRehash(HashedPassword $hashedPassword);

    /**
     * Verifies that the given HashedPassword matches the given Password.
     *
     * @param Password       $password
     * @param HashedPassword $hashedPassword
     *
     * @return bool
     */
    public function verify(Password $password, HashedPassword $hashedPassword);
}
