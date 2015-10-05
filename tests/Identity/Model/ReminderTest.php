<?php

namespace OG\Account\Test\Domain\Identity\Model;

use Mockery as m;
use OG\Account\Domain\Identity\Model\DateTime;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\Reminder;
use OG\Account\Domain\Identity\Model\ReminderCode;
use OG\Account\Domain\Identity\Model\ReminderId;

class ReminderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ReminderId
     */
    private $id;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var ReminderCode
     */
    private $code;

    public function setUp()
    {
        $this->id = ReminderId::fromString('3169e2af-90a3-49b3-a822-7854f05972ae');
        $this->email = new Email('local@domain.com');
        $this->code = ReminderCode::generate();
    }

    /**
     * @test
     */
    public function it_should_require_reminder_id()
    {
        $this->setExpectedException('Exception');
        Reminder::request(null, $this->email, $this->code);
    }

    /**
     * @test
     */
    public function it_should_require_email()
    {
        $this->setExpectedException('Exception');
        Reminder::request($this->id, null, $this->code);
    }

    /**
     * @test
     */
    public function it_should_require_code()
    {
        $this->setExpectedException('Exception');
        Reminder::request($this->id, $this->email, null);
    }

    /**
     * @test
     */
    public function it_should_create_reminder()
    {
        $creation_time = new DateTime();

        $reminder = Reminder::request($this->id, $this->email, $this->code);

        $this->assertInstanceOf(Reminder::class, $reminder);
        $this->assertEquals($this->id, $reminder->getId());
        $this->assertEquals($this->email, $reminder->getEmail());
        $this->assertEquals($this->code, $reminder->getCode());
        $this->assertEquals($creation_time, $reminder->getCreatedAt());
        $this->assertEquals(1, count($reminder->releaseEvents()));
    }

    /**
     * @test
     */
    public function it_should_be_valid_when_not_expired()
    {
        $reminder = Reminder::request($this->id, $this->email, $this->code);

        $this->assertTrue($reminder->isValid());
    }

    /**
     * @test
     */
    public function it_should_be_invalid_when_expired()
    {
        DateTime::setTestDateTime(new DateTime('yesterday'));
        $reminder = Reminder::request($this->id, $this->email, $this->code);
        DateTime::clearTestDateTime();

        $this->assertFalse($reminder->isValid());
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = Reminder::request($this->id, $this->email, $this->code);
        $two = Reminder::request($this->id, $this->email, $this->code);
        $three = Reminder::request(ReminderId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc'), $this->email, $this->code);

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }

    public function tearDown()
    {
        m::close();
    }
}
