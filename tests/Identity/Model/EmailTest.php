<?php

namespace OG\Account\Tests\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\Email;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_accept_valid_email()
    {
        $email = new Email('local@domain.com');

        $this->assertInstanceOf(Email::class, $email);
    }

    /**
     * @test
     */
    public function it_should_require_valid_email()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Email('invalid_email');
    }

    /**
     * @test
     */
    public function it_should_require_valid_string_email()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Email([]);
    }

    /**
     * @test
     */
    public function it_should_require_email_under_or_100_characters()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Email(
            'this_is_an_email_that_is_101_characters_long@aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa.com'
        );
    }

    /**
     * @test
     */
    public function it_should_require_not_null_password()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Email(null);
    }

    /**
     * @test
     */
    public function it_should_accept_email_from_string()
    {
        $email = Email::fromString('local@domain.com');

        $this->assertEquals('local@domain.com', (string) $email);
    }

    /**
     * @test
     */
    public function it_should_return_email_as_string()
    {
        $email = new Email('local@domain.com');

        $this->assertEquals('local@domain.com', (string) $email);
        $this->assertEquals('local@domain.com', $email->toString());
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = new Email('local@domain.com');
        $two = new Email('local@domain.com');
        $three = new Email('local@domain.net');

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
