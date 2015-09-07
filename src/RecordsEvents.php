<?php

namespace OG\Account\Domain;

trait RecordsEvents
{
    /**
     * @var DomainEvent[]
     */
    private $pendingEvents = [];

    /**
     * Record that an event has occurred.
     *
     * @param DomainEvent $event
     *
     * @return static
     */
    public function recordThat(DomainEvent $event)
    {
        $this->pendingEvents[] = $event;

        return $this;
    }

    /**
     * Release the pending events.
     *
     * @return DomainEvent[]
     */
    public function releaseEvents()
    {
        $events = $this->pendingEvents;
        $this->pendingEvents = [];

        return $events;
    }
}
