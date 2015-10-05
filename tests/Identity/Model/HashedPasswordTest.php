<?php

namespace OG\Account\Tests\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\HashedPassword;

class HashedPasswordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_require_hashed_password()
    {
        $this->setExpectedException('\Exception');
        new HashedPassword();
    }

    /**
     * @test
     */
    public function it_should_accept_valid_hashed_password()
    {
        $password = new HashedPassword('valid_hashed_password');

        $this->assertInstanceOf(HashedPassword::class, $password);
    }

    /**
     * @test
     */
    public function it_should_require_valid_hashed_password()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new HashedPassword([]);
    }

    /**
     * @test
     */
    public function it_should_require_not_null_hashed_password()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new HashedPassword(null);
    }

    /**
     * @test
     */
    public function it_should_accept_hashed_password_from_string()
    {
        $password = HashedPassword::fromString('valid_hashed_password');

        $this->assertEquals('valid_hashed_password', (string) $password);
    }

    /**
     * @test
     */
    public function it_should_return_hashed_password_as_string()
    {
        $password = new HashedPassword('valid_hashed_password');

        $this->assertEquals('valid_hashed_password', (string) $password);
        $this->assertEquals('valid_hashed_password', $password->toString());
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = new HashedPassword('valid_hashed_password');
        $two = new HashedPassword('valid_hashed_password');
        $three = new HashedPassword('other_hashed_password');

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
