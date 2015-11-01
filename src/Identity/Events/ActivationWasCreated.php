<?php

namespace OG\Account\Domain\Identity\Events;

use OG\Core\Domain\DomainEvent;

/**
 * Event fired when an activation is created.
 */
class ActivationWasCreated implements DomainEvent
{
    // TODO: Add extra information to the event.
    public function __construct($code)
    {
    }
}
