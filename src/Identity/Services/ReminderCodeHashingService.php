<?php

namespace OG\Account\Domain\Identity\Services;

use OG\Account\Domain\Identity\Model\HashedReminderCode;
use OG\Account\Domain\Identity\Model\ReminderCode;

/**
 * Hashes and verifies a ReminderCode.
 */
interface ReminderCodeHashingService
{
    /**
     * Create a new HashedReminderCode.
     *
     * @param ReminderCode $code
     *
     * @return HashedReminderCode
     */
    public function hash(ReminderCode $code);

    /**
     * Checks if the current hash needs to be rehashed.
     *
     * @param HashedReminderCode $hashedCode
     *
     * @return bool
     */
    public function needsRehash(HashedReminderCode $hashedCode);

    /**
     * Verifies that the given HashedReminderCode matches the given ReminderCode.
     *
     * @param ReminderCode       $code
     * @param HashedReminderCode $hashedCode
     *
     * @return bool
     */
    public function verify(ReminderCode $code, HashedReminderCode $hashedCode);
}
