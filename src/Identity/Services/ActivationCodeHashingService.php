<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\ActivationCode;
use OG\Account\Domain\Identity\Model\HashedActivationCode;

/**
 * Hashes and verifies a ActivationCode.
 */
interface ActivationCodeHashingService
{
    /**
     * Create a new HashedActivationCode.
     *
     * @param ActivationCode $code
     *
     * @return HashedActivationCode
     */
    public function hash(ActivationCode $code);

    /**
     * Checks if the current hash needs to be rehashed.
     *
     * @param HashedActivationCode $hashedCode
     *
     * @return bool
     */
    public function needsRehash(HashedActivationCode $hashedCode);

    /**
     * Verifies that the given HashedActivationCode matches the given ActivationCode.
     *
     * @param ActivationCode       $code
     * @param HashedActivationCode $hashedCode
     *
     * @return bool
     */
    public function verify(ActivationCode $code, HashedActivationCode $hashedCode);
}
