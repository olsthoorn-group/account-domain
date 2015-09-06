<?php

namespace OG\Account\Tests\Domain;

use OG\Account\Domain\UuidIdentifier;

class UuidIdentifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_create_new_user_id()
    {
        $id = TestUuid1::generate();
        $this->assertInstanceOf(TestUuid1::class, $id);
    }

    /**
     * @test
     */
    public function it_should_generate_valid_uuids()
    {
        $id = TestUuid1::generate();
        $pattern = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i';
        $this->assertRegExp($pattern, (string) $id);
    }

    /**
     * @test
     */
    public function it_should_create_user_id_from_string()
    {
        $id = TestUuid1::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc');
        $this->assertInstanceOf(TestUuid1::class, $id);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_on_malformed_uuid()
    {
        $this->setExpectedException('Assert\AssertionFailedException');
        TestUuid1::fromString('bad-uuid');
    }

    /**
     * @test
     */
    public function it_should_return_user_id_as_string()
    {
        $id = TestUuid1::fromString('d16f9fe7-e947-460e-99f6-2d64d65f46bc');
        $this->assertEquals('d16f9fe7-e947-460e-99f6-2d64d65f46bc', (string) $id);
    }

    /**
     * @test
     */
    public function it_should_have_equality()
    {
        $id1 = TestUuid1::generate();
        $id2 = TestUuid1::fromString((string) $id1);
        $id3 = TestUuid1::generate();
        $id4 = TestUuid1::fromString('c3bc7f4c-804a-4a7c-8313-51fd1b7baf52');
        $id5 = TestUuid1::fromString('c3bc7f4c-804a-4a7c-8313-51fd1b7baf52');
        $this->assertTrue($id1->equals($id2), 'Two generated UuidIdentifier objects with the same value should be equal.');
        $this->assertTrue(!$id1->equals($id3), 'Two random UuidIdentifier objects should not be equal.');
        $this->assertTrue($id4->equals($id5), 'Two instantiated UuidIdentifier objects with the same value should be equal.');
    }

    /**
     * @test
     */
    public function same_value_but_different_types_should_not_be_considered_equal()
    {
        $id1 = TestUuid1::generate();
        $id2 = TestUuid2::fromString((string) $id1);
        $this->assertFalse($id1->equals($id2));
    }
}

final class TestUuid1 extends UuidIdentifier
{
}

final class TestUuid2 extends UuidIdentifier
{
}
