<?php

namespace OG\Account\Tests\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\HashedReminderCode;

class HashedReminderCodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_require_hashed_reminder_code()
    {
        $this->setExpectedException('\Exception');
        new HashedReminderCode();
    }

    /**
     * @test
     */
    public function it_should_accept_valid_hashed_reminder_code()
    {
        $code = new HashedReminderCode('valid_hashed_reminder_code');

        $this->assertInstanceOf(HashedReminderCode::class, $code);
    }

    /**
     * @test
     */
    public function it_should_require_valid_hashed_reminder_code()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new HashedReminderCode([]);
    }

    /**
     * @test
     */
    public function it_should_require_not_null_hashed_reminder_code()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new HashedReminderCode(null);
    }

    /**
     * @test
     */
    public function it_should_accept_hashed_reminder_code_from_string()
    {
        $code = HashedReminderCode::fromString('valid_hashed_reminder_code');

        $this->assertEquals('valid_hashed_reminder_code', (string) $code);
    }

    /**
     * @test
     */
    public function it_should_return_hashed_reminder_code_as_string()
    {
        $code = new HashedReminderCode('valid_hashed_reminder_code');

        $this->assertEquals('valid_hashed_reminder_code', (string) $code);
        $this->assertEquals('valid_hashed_reminder_code', $code->toString());
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = new HashedReminderCode('valid_hashed_reminder_code');
        $two = new HashedReminderCode('valid_hashed_reminder_code');
        $three = new HashedReminderCode('other_hashed_reminder_code');

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
