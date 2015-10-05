<?php

namespace OG\Account\Tests\Domain;

use OG\Account\Domain\DomainEvent;
use OG\Account\Domain\RecordsEvents;

class RecordsEventsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_initially_have_no_events()
    {
        $recordsEvents = $this->getObjectForTrait(RecordsEvents::class);

        $this->assertEmpty($recordsEvents->releaseEvents());

        return $recordsEvents;
    }

    /**
     * @test
     * @depends it_should_initially_have_no_events
     *
     * @param RecordsEvents $recordsEvents
     */
    public function it_should_return_events_if_added($recordsEvents)
    {
        $domainEvent = new DomainTestEvent();

        $recordsEvents->recordThat($domainEvent);

        $this->assertEquals([$domainEvent], $recordsEvents->releaseEvents());
    }

    /**
     * @test
     * @depends it_should_initially_have_no_events
     *
     * @param RecordsEvents $recordsEvents
     *
     * @return RecordsEvents
     */
    public function it_should_return_events_in_the_right_order($recordsEvents)
    {
        $domainEvent = new DomainTestEvent();
        $domainEvent2 = new DomainTestEvent();

        $recordsEvents->recordThat($domainEvent);
        $recordsEvents->recordThat($domainEvent2);

        $this->assertEquals([$domainEvent, $domainEvent2], $recordsEvents->releaseEvents());

        return $recordsEvents;
    }

    /**
     * @test
     * @depends it_should_return_events_in_the_right_order
     *
     * @param RecordsEvents $recordsEvents
     */
    public function it_should_clear_all_the_events_when_they_are_returned($recordsEvents)
    {
        $this->assertEmpty($recordsEvents->releaseEvents());
    }
}

class DomainTestEvent implements DomainEvent
{
}
