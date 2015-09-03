<?php

namespace OG\Account\Tests\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\Email;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_require_email()
    {
        $this->setExpectedException('\Exception');
        new Email();
    }

    /**
     * @test
     */
    public function it_should_require_valid_email()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Email('this_is_not_a_valid_email');
    }

    /**
     * @test
     */
    public function it_should_require_not_null()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Email(null);
    }

    /**
     * @test
     */
    public function it_should_require_not_empty()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Email('');
    }

    /**
     * @test
     */
    public function it_should_require_email_under_100_characters()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Email(
            'this_is_not_a_valid_email_this_is_not_a_valid_email_this_is_not_a_valid_email_this_is_not_a_valid_email'
        );
    }

    /**
     * @test
     */
    public function it_should_accept_valid_email()
    {
        $email = new Email('name@domain.com');
        $this->assertInstanceOf(Email::class, $email);
    }

    /**
     * @test
     */
    public function it_should_return_as_string()
    {
        $email = new Email('name@domain.com');
        $this->assertEquals('name@domain.com', (string) $email);
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = new Email('name@domain.com');
        $two = new Email('name@domain.com');
        $three = new Email('name@domain.net');
        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
