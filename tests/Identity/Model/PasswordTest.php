<?php

namespace OG\Account\Tests\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\Password;

class PasswordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_require_password()
    {
        $this->setExpectedException('\Exception');
        new Password();
    }

    /**
     * @test
     */
    public function it_should_accept_valid_password()
    {
        $password = new Password('valid_password');
        $this->assertInstanceOf(Password::class, $password);
    }

    /**
     * @test
     */
    public function it_should_require_valid_password()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Password([]);
    }

    /**
     * @test
     */
    public function it_should_require_password_above_or_8_characters()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Password('1234567');
    }

    /**
     * @test
     */
    public function it_should_require_password_under_or_100_characters()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Password(
            'this_is_a_password_that_is_101_characters_loooooooooooooooooooooooooooooooooooooooooooooooooooooooong'
        );
    }

    /**
     * @test
     */
    public function it_should_require_not_null_password()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Password(null);
    }

    /**
     * @test
     */
    public function it_should_accept_password_from_string()
    {
        $password = Password::fromString('valid_password');
        $this->assertEquals('valid_password', (string) $password);
    }

    /**
     * @test
     */
    public function it_should_return_password_as_string()
    {
        $password = new Password('valid_password');
        $this->assertEquals('valid_password', (string) $password);
        $this->assertEquals('valid_password', $password->toString());
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = new Password('valid_password');
        $two = new Password('valid_password');
        $three = new Password('other_password');
        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
