<?php

namespace OG\Account\Test\Domain\Identity\Model;

use Mockery as m;
use OG\Account\Domain\Identity\Model\Email;
use OG\Account\Domain\Identity\Model\HashedReminderCode;
use OG\Account\Domain\Identity\Model\Reminder;
use OG\Account\Domain\Identity\Model\ReminderCode;
use OG\Account\Domain\Identity\Model\ReminderId;
use OG\Core\Domain\Model\DateTime;

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

    /**
     * @var HashedReminderCode
     */
    private $hashedCode;

    public function setUp()
    {
        $this->id = ReminderId::fromString('3169e2af-90a3-49b3-a822-7854f05972ae');
        $this->email = new Email('local@domain.com');
        $this->code = ReminderCode::generate();
        $this->hashedCode = new HashedReminderCode('valid_reminder_code');
    }

    /**
     * @test
     */
    public function it_should_create_reminder()
    {
        $creation_time = DateTime::now();

        $reminder = Reminder::request($this->id, $this->email, $this->code, $this->hashedCode);

        $this->assertInstanceOf(Reminder::class, $reminder);
        $this->assertEquals($this->id, $reminder->getId());
        $this->assertEquals($this->email, $reminder->getAlias());
        $this->assertEquals($this->hashedCode, $reminder->getCode());
        $this->assertEquals($creation_time, $reminder->getCreatedAt());
        $this->assertEquals(1, count($reminder->releaseEvents()));
    }

    /**
     * @test
     */
    public function it_should_be_valid_when_not_expired()
    {
        $reminder = Reminder::request($this->id, $this->email, $this->code, $this->hashedCode);

        $this->assertTrue($reminder->isValid());
    }

    /**
     * @test
     */
    public function it_should_be_invalid_when_expired()
    {
        DateTime::setTestDateTime(new DateTime('yesterday'));
        $reminder = Reminder::request($this->id, $this->email, $this->code, $this->hashedCode);
        DateTime::clearTestDateTime();

        $this->assertFalse($reminder->isValid());
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = Reminder::request($this->id, $this->email, $this->code, $this->hashedCode);
        $two = Reminder::request($this->id, $this->email, $this->code, $this->hashedCode);
        $three = Reminder::request(ReminderId::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc'), $this->email, $this->code, $this->hashedCode);

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }

    public function tearDown()
    {
        m::close();
    }
}
