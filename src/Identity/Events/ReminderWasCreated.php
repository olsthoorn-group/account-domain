<?php

namespace OG\Account\Domain\Identity\Events;

use OG\Account\Domain\Identity\Model\ReminderCode;
use OG\Core\Domain\DomainEvent;
use OG\Core\Domain\Model\DateTime;

/**
 * Event fired when a reminder is created.
 */
class ReminderWasCreated implements DomainEvent
{
    public $reminderCode;
    public $happened_at;

    /**
     * ReminderWasCreated constructor.
     *
     * @param ReminderCode $code
     */
    public function __construct(ReminderCode $code)
    {
        $this->reminderCode = $code;
        $this->happened_at = DateTime::now();
    }
}
