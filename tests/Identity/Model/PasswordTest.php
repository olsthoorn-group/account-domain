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
    public function it_should_require_valid_password()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Password('abc');
    }

    /**
     * @test
     */
    public function it_should_require_password_under_100_characters()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Password(
            'this_is_not_a_valid_password_this_is_not_a_valid_password_this_is_not_a_valid_password_this_is_not_a_valid'
        );
    }

    /**
     * @test
     */
    public function it_should_require_not_null()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Password(null);
    }

    /**
     * @test
     */
    public function it_should_require_not_empty()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new Password('');
    }

    /**
     * @test
     */
    public function it_should_accept_valid_password()
    {
        $password = new Password('ffsfewefhwuehfuiwhfiuwiufgiuwgewiugwefiuwbw');
        $this->assertInstanceOf(Password::class, $password);
    }

    /**
     * @test
     */
    public function it_should_return_as_string()
    {
        $password = new Password('qwertyuiop');
        $this->assertEquals('qwertyuiop', (string) $password);
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = new Password('qwertyuiop');
        $two = new Password('qwertyuiop');
        $three = new Password('asdfghjkl');
        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
