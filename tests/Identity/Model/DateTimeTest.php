<?php

namespace OG\Account\Test\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\DateTime;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_create_datetime()
    {
        $this->assertInstanceOf(DateTime::class, DateTime::now());
        $this->assertInstanceOf(DateTime::class, new DateTime());
        $this->assertInstanceOf(DateTime::class, new DateTime('now'));
        $this->assertInstanceOf(DateTime::class, new DateTime('now', new \DateTimeZone('utc')));
    }

    /**
     * @test
     */
    public function it_should_return_normal_datetime()
    {
        $tomorrow = new DateTime('tomorrow');
        $yesterday = new DateTime('yesterday');
        DateTime::setTestDateTime($yesterday);

        $this->assertEquals($tomorrow, new DateTime('tomorrow'));

        DateTime::clearTestDateTime();
    }

    /**
     * @test
     */
    public function it_should_return_test_datetime_on_now()
    {
        $yesterday = new DateTime('yesterday');
        DateTime::setTestDateTime($yesterday);

        $this->assertEquals($yesterday, DateTime::getTestDateTime());
        $this->assertEquals($yesterday, DateTime::now());

        DateTime::clearTestDateTime();
    }

    /**
     * @test
     */
    public function it_should_clear_test_datetime()
    {
        $now = DateTime::now();
        $yesterday = new DateTime('yesterday');
        DateTime::setTestDateTime($yesterday);
        $this->assertEquals($yesterday, DateTime::now());

        DateTime::clearTestDateTime();

        $this->assertEquals($now, DateTime::now());
    }

    /**
     * @test
     */
    public function it_should_accept_datetime_from_string()
    {
        $datetime = DateTime::fromString('2000-01-01 00:00:00|UTC');

        $this->assertEquals('2000-01-01 00:00:00|UTC', (string) $datetime);
    }

    /**
     * @test
     */
    public function it_should_return_datetime_as_string()
    {
        DateTime::setTestDateTime(new DateTime('2000-01-01 00:00:00', new \DateTimeZone('UTC')));
        $datetime = DateTime::now();
        DateTime::clearTestDateTime();

        $this->assertEquals('2000-01-01 00:00:00|UTC', (string) $datetime);
        $this->assertEquals('2000-01-01 00:00:00|UTC', $datetime->toString());
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = new DateTime('2000-01-01 00:00:00', new \DateTimeZone('UTC'));
        $two = new DateTime('2000-01-01 00:00:00', new \DateTimeZone('UTC'));
        $three = new DateTime('2000-01-01 00:00:01', new \DateTimeZone('UTC'));

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
