<?php

namespace OG\Account\Test\Domain\Identity\Model;

use OG\Account\Domain\Identity\Model\ActivationCode;

class ActivationCodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_generate_new_codes()
    {
        $code = ActivationCode::generate();

        $this->assertInstanceOf(ActivationCode::class, $code);
    }

    /**
     * @test
     */
    public function it_should_generate_valid_hexadecimals()
    {
        $code = ActivationCode::generate();

        $pattern = '/^[a-f0-9]*$/';
        $this->assertRegExp($pattern, (string) $code);
    }

    /**
     * @test
     */
    public function it_should_create_a_code_from_a_string()
    {
        $code = ActivationCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865');

        $this->assertInstanceOf(ActivationCode::class, $code);
    }

    /**
     * @test
     */
    public function it_should_require_strings()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        ActivationCode::fromString([]);
    }

    /**
     * @test
     */
    public function it_should_require_hexadecimals()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        ActivationCode::fromString('invalid_hexadecimal');
    }

    /**
     * @test
     */
    public function it_should_require_valid_code_lengths()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        ActivationCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd8');
    }

    /**
     * @test
     */
    public function it_should_require_valid_code_lengths_2()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        ActivationCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd86583');
    }

    /**
     * @test
     */
    public function it_should_return_as_string()
    {
        $code = ActivationCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865');

        $this->assertEquals('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865', (string) $code);
        $this->assertEquals('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865', $code->toString());
    }

    /**
     * @test
     */
    public function it_should_test_equality()
    {
        $one = ActivationCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865');
        $two = ActivationCode::fromString('441750964b8ca7b4b55b7a1f69a15275e7902c39e824d89ecbf674a12e4dd865');
        $three = ActivationCode::fromString('6e163a22ea8f4deee76d61a7c2f8192c8e5ab1d50741155e0f0b12e335cafa91');

        $this->assertTrue($one->equals($two));
        $this->assertFalse($one->equals($three));
    }
}
