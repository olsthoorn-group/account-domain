<?php

namespace OG\Account\Test\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\ReminderCode;

class ReminderCodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function should_generate_new_code()
    {
        $code = ReminderCode::generate();

        $this->assertInstanceOf(ReminderCode::class, $code);
    }

    /**
     * @test
     */
    public function it_should_require_valid_code_email()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        ReminderCode::fromString([]);
    }

    /**
     * @test
     */
    public function should_create_a_code_from_a_string()
    {
        $code = ReminderCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865');

        $this->assertInstanceOf(ReminderCode::class, $code);
    }

    /**
     * @test
     */
    public function should_return_as_string()
    {
        $code = ReminderCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865');

        $this->assertEquals('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865', (string) $code);
        $this->assertEquals('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865', $code->toString());
    }

    /**
     * @test
     */
    public function should_test_equality()
    {
        $one = ReminderCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865');
        $two = ReminderCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865');
        $three = ReminderCode::fromString('6e163a22ea8f4deee76d61a7c2f8192c8e5ab1d50741155e0f0b12e335cafa91');

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
