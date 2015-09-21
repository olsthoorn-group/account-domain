<?php

namespace OG\Account\Domain;

/**
 * Indicates that an object can record events.
 */
interface HasEvents
{
    /**
     * Record that an event has occurred.
     *
     * @param DomainEvent $event
     *
     * @return static
     */
    public function recordThat(DomainEvent $event);

    /**
     * Release the pending events.
     *
     * @return DomainEvent[]
     */
    public function releaseEvents();
}
