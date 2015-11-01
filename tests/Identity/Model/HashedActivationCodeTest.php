<?php

namespace OG\Account\Tests\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\HashedActivationCode;

class HashedActivationCodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_accept_valid_hashed_activation_code()
    {
        $code = new HashedActivationCode('valid_hashed_activation_code');

        $this->assertInstanceOf(HashedActivationCode::class, $code);
    }

    /**
     * @test
     */
    public function it_should_require_valid_hashed_activation_code()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new HashedActivationCode([]);
    }

    /**
     * @test
     */
    public function it_should_require_not_null_hashed_activation_code()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        new HashedActivationCode(null);
    }

    /**
     * @test
     */
    public function it_should_accept_hashed_activation_code_from_string()
    {
        $code = HashedActivationCode::fromString('valid_hashed_activation_code');

        $this->assertEquals('valid_hashed_activation_code', (string) $code);
    }

    /**
     * @test
     */
    public function it_should_return_hashed_activation_code_as_string()
    {
        $code = new HashedActivationCode('valid_hashed_activation_code');

        $this->assertEquals('valid_hashed_activation_code', (string) $code);
        $this->assertEquals('valid_hashed_activation_code', $code->toString());
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $one = new HashedActivationCode('valid_hashed_activation_code');
        $two = new HashedActivationCode('valid_hashed_activation_code');
        $three = new HashedActivationCode('other_valid_hashed_activation_code');

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
